const SftpClient = require("ssh2-sftp-client");
const path = require("path");
const fs = require("fs");
const { ENV_NAME, SFTP_CONFIG, REMOTE_THEME } = require("./env");

const GIT_ROOT = path.resolve(__dirname, "../../../..");           // app/public/
const LOCAL_THEME = path.join(GIT_ROOT, "wp-content/themes/emily-chelsea");

const EXCLUDES = [
	"node_modules",
	".cache",
	".DS_Store",
	".git",
	path.join("src", "node_modules"),
	"deploy",
];

function shouldExclude(remoteRelPath) {
	return EXCLUDES.some(function (e) {
		return remoteRelPath === e || remoteRelPath.startsWith(e + path.sep);
	});
}

const DRY_RUN = process.argv.includes("--dry-run");
const TEST_CONNECT = process.argv.includes("--test-connect");
const pathArg = process.argv.find(function (a) {
	return a.startsWith("--path=");
});
// Optional subpath, e.g. --path=template-parts to only pull that folder
const SUBPATH = pathArg ? pathArg.split("=")[1].replace(/^\/+|\/+$/g, "") : "";

async function testConnect() {
	const sftp = new SftpClient();
	try {
		await sftp.connect(SFTP_CONFIG);
		console.log("[pull] (" + ENV_NAME + ") Connected to " + SFTP_CONFIG.host);
		const list = await sftp.list(REMOTE_THEME);
		console.log("[pull] " + REMOTE_THEME + " has " + list.length + " item(s)");
	} catch (err) {
		console.error("[pull] Connection failed:", err.message);
		process.exit(1);
	} finally {
		await sftp.end();
	}
}

async function walkRemote(sftp, remoteDir, relDir, out) {
	const entries = await sftp.list(remoteDir);
	for (const entry of entries) {
		const remoteRelPath = relDir ? path.join(relDir, entry.name) : entry.name;
		if (shouldExclude(remoteRelPath)) continue;

		const remotePath = remoteDir + "/" + entry.name;
		if (entry.type === "d") {
			await walkRemote(sftp, remotePath, remoteRelPath, out);
		} else if (entry.type === "-") {
			out.push({ remotePath, relPath: remoteRelPath });
		}
	}
	return out;
}

async function pull() {
	const sftp = new SftpClient();
	try {
		await sftp.connect(SFTP_CONFIG);

		const remoteRoot = SUBPATH ? REMOTE_THEME + "/" + SUBPATH : REMOTE_THEME;
		const relRoot = SUBPATH;

		console.log("[pull] (" + ENV_NAME + ") Scanning " + remoteRoot + " ...");
		const files = await walkRemote(sftp, remoteRoot, relRoot, []);
		console.log("[pull] " + files.length + " file(s) found on live.");

		if (DRY_RUN) {
			files.forEach(function (f) {
				console.log("  ~ " + f.relPath);
			});
			console.log("[pull] Dry run, nothing downloaded.");
			return;
		}

		for (const file of files) {
			const localPath = path.join(LOCAL_THEME, file.relPath);
			fs.mkdirSync(path.dirname(localPath), { recursive: true });
			await sftp.get(file.remotePath, localPath);
			console.log("  ~ " + file.relPath);
		}

		console.log("[pull] Done. Pulled " + files.length + " file(s) from live.");
	} catch (err) {
		console.error("[pull] Failed:", err.message);
		process.exit(1);
	} finally {
		await sftp.end();
	}
}

if (TEST_CONNECT) {
	testConnect();
} else {
	pull();
}
