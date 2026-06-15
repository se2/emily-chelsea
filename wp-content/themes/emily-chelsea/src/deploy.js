const SftpClient = require("ssh2-sftp-client");
const path = require("path");
const { execSync } = require("child_process");
const fs = require("fs");

const SFTP_CONFIG = {
	host: "emilychels1stg.sftp.wpengine.com",
	port: 2222,
	username: "emilychels1stg-tr",
	password: "45e4h27U76S553q",
	readyTimeout: 30000,
};

const GIT_ROOT = path.resolve(__dirname, "../../../.."); // app/public/
const REMOTE_THEME = "/wp-content/themes/emily-chelsea-v2";
const THEME_PREFIX = "wp-content/themes/emily-chelsea/";

const EXCLUDES = [
	"node_modules",
	".cache",
	".DS_Store",
	path.join("src", "deploy.js"),
];

function shouldExclude(gitRelPath) {
	const themeRel = gitRelPath.slice(THEME_PREFIX.length);
	return EXCLUDES.some(function (e) {
		return themeRel === e || themeRel.startsWith(e + path.sep);
	});
}

const rangeArg = process.argv.find(function (a) {
	return a.startsWith("--range=");
});
const RANGE = rangeArg ? rangeArg.split("=")[1] : "HEAD~1..HEAD";

function gitDiff(filter) {
	try {
		return execSync(
			"git diff " + RANGE + " --name-only --diff-filter=" + filter,
			{
				cwd: GIT_ROOT,
				encoding: "utf8",
			},
		)
			.trim()
			.split("\n")
			.filter(function (f) {
				return f.startsWith(THEME_PREFIX);
			});
	} catch (e) {
		return [];
	}
}

async function deploy() {
	const toUpload = gitDiff("ACMRT").filter(function (f) {
		return !shouldExclude(f) && fs.existsSync(path.join(GIT_ROOT, f));
	});
	const toDelete = gitDiff("D").filter(function (f) {
		return !shouldExclude(f);
	});

	if (toUpload.length === 0 && toDelete.length === 0) {
		console.log("[deploy] No theme files changed, skipping.");
		return;
	}

	console.log(
		"[deploy] " +
			toUpload.length +
			" file(s) to upload, " +
			toDelete.length +
			" to delete",
	);

	const sftp = new SftpClient();
	try {
		await sftp.connect(SFTP_CONFIG);

		for (const file of toUpload) {
			const localPath = path.join(GIT_ROOT, file);
			const remotePath =
				REMOTE_THEME + "/" + file.slice(THEME_PREFIX.length);
			const remoteDir = path.posix.dirname(remotePath);

			try {
				await sftp.mkdir(remoteDir, true);
			} catch (e) {
				/* dir may exist */
			}

			await sftp.put(localPath, remotePath);
			console.log("  + " + file.slice(THEME_PREFIX.length));
		}

		for (const file of toDelete) {
			const remotePath =
				REMOTE_THEME + "/" + file.slice(THEME_PREFIX.length);
			try {
				await sftp.delete(remotePath);
				console.log("  - " + file.slice(THEME_PREFIX.length));
			} catch (e) {
				/* file may not exist */
			}
		}

		console.log("[deploy] Done.");
	} catch (err) {
		console.error("[deploy] Failed:", err.message);
		process.exit(1);
	} finally {
		await sftp.end();
	}
}

deploy();
