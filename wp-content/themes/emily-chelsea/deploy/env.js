const path = require("path");
const fs = require("fs");

const envArg = process.argv.find(function (a) {
	return a.startsWith("--env=");
});
const ENV_NAME = envArg ? envArg.split("=")[1] : "production";

const envFile = path.join(__dirname, ".env." + ENV_NAME);
if (!fs.existsSync(envFile)) {
	console.error(
		"[env] Missing " +
			envFile +
			" — copy .env.example to .env." +
			ENV_NAME +
			" and fill in credentials.",
	);
	process.exit(1);
}

require("dotenv").config({ path: envFile });

const REQUIRED = [
	"SFTP_HOST",
	"SFTP_PORT",
	"SFTP_USER",
	"SFTP_PASSWORD",
	"REMOTE_THEME_PATH",
];
const missing = REQUIRED.filter(function (key) {
	return !process.env[key];
});
if (missing.length) {
	console.error(
		"[env] Missing required var(s) in .env." +
			ENV_NAME +
			": " +
			missing.join(", "),
	);
	process.exit(1);
}

module.exports = {
	ENV_NAME: ENV_NAME,
	SFTP_CONFIG: {
		host: process.env.SFTP_HOST,
		port: Number(process.env.SFTP_PORT),
		username: process.env.SFTP_USER,
		password: process.env.SFTP_PASSWORD,
		readyTimeout: 30000,
	},
	REMOTE_THEME: process.env.REMOTE_THEME_PATH,
};
