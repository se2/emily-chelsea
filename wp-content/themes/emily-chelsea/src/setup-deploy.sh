#!/bin/bash

REPO_ROOT="$(git rev-parse --show-toplevel)"
HOOK_FILE="$REPO_ROOT/.git/hooks/pre-push"

mkdir -p "$REPO_ROOT/.git/hooks"

cat > "$HOOK_FILE" << 'EOF'
#!/bin/bash

REPO_ROOT="$(git rev-parse --show-toplevel)"
THEME_SRC="$REPO_ROOT/wp-content/themes/emily-chelsea/src"

export NVM_DIR="$HOME/.nvm"
[ -s "$NVM_DIR/nvm.sh" ] && \. "$NVM_DIR/nvm.sh"

while read local_ref local_sha remote_ref remote_sha; do
  if [ "$remote_sha" = "0000000000000000000000000000000000000000" ]; then
    RANGE="HEAD~1..HEAD"
  else
    RANGE="$remote_sha..$local_sha"
  fi

  echo ""
  echo "[deploy] Pushing theme to WPEngine... (range: $RANGE)"
  node "$THEME_SRC/deploy.js" --range="$RANGE"

  if [ $? -eq 0 ]; then
    echo "[deploy] ✓ Theme pushed successfully!"
  else
    echo "[deploy] ✗ Deploy failed - check SFTP connection."
    exit 1
  fi
done
EOF

chmod +x "$HOOK_FILE"
echo "pre-push hook created at $HOOK_FILE"
