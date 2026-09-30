const SftpClient = require("ssh2-sftp-client");
const path = require("path");
const { execSync } = require("child_process");
const fs = require("fs");
const { ENV_NAME, SFTP_CONFIG, REMOTE_THEME } = require("./env");

const GIT_ROOT = path.resolve(__dirname, "../../../.."); // app/public/
const THEME_PREFIX = "wp-content/themes/emily-chelsea/";

const EXCLUDES = [
	"node_modules",
	".cache",
	".DS_Store",
	"deploy",
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
const DRY_RUN = process.argv.includes("--dry-run");
const TEST_CONNECT = process.argv.includes("--test-connect");

const filesArg = process.argv.find(function (a) {
	return a.startsWith("--files=");
});
// Explicit file list (comma-separated, relative to the theme root), e.g.
// --files=inc/foo.php,template-parts/bar.php — bypasses git diff entirely so it
// deploys exactly these files regardless of what else is uncommitted/staged.
const FILES = filesArg
	? filesArg
			.slice("--files=".length)
			.split(",")
			.map(function (f) {
				return f.trim();
			})
			.filter(Boolean)
	: null;

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

async function testConnect() {
	const sftp = new SftpClient();
	try {
		await sftp.connect(SFTP_CONFIG);
		console.log(
			"[deploy] (" + ENV_NAME + ") Connected to " + SFTP_CONFIG.host,
		);
		const list = await sftp.list(REMOTE_THEME);
		console.log(
			"[deploy] " + REMOTE_THEME + " has " + list.length + " item(s)",
		);
	} catch (err) {
		console.error("[deploy] Connection failed:", err.message);
		process.exit(1);
	} finally {
		await sftp.end();
	}
}

async function deploy() {
	let toUpload, toDelete;

	if (FILES) {
		// Explicit file list is an intentional whitelist — EXCLUDES (which blocks the
		// whole "deploy" folder from the automatic git-diff flow) does not apply here.
		toUpload = FILES.map(function (f) {
			return THEME_PREFIX + f;
		}).filter(function (f) {
			return fs.existsSync(path.join(GIT_ROOT, f));
		});
		toDelete = [];
	} else {
		toUpload = gitDiff("ACMRT").filter(function (f) {
			return !shouldExclude(f) && fs.existsSync(path.join(GIT_ROOT, f));
		});
		toDelete = gitDiff("D").filter(function (f) {
			return !shouldExclude(f);
		});
	}

	if (toUpload.length === 0 && toDelete.length === 0) {
		console.log("[deploy] No theme files changed, skipping.");
		return;
	}

	console.log(
		"[deploy] (" +
			ENV_NAME +
			") " +
			toUpload.length +
			" file(s) to upload, " +
			toDelete.length +
			" to delete",
	);

	if (DRY_RUN) {
		toUpload.forEach(function (file) {
			console.log("  + " + file.slice(THEME_PREFIX.length));
		});
		toDelete.forEach(function (file) {
			console.log("  - " + file.slice(THEME_PREFIX.length));
		});
		console.log("[deploy] Dry run, nothing uploaded.");
		return;
	}

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

if (TEST_CONNECT) {
	testConnect();
} else {
	deploy();
}
