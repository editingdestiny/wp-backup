#!/bin/bash
# filepath: /home/sd22750/wp-backup/backup_wordpress_to_git.sh

set -e

# CONFIG
CONTAINER=wordpress
BACKUP_DIR=/home/sd22750/wp-backup
GIT_BRANCH=main

# 1. Copy wp-content from the running container
docker cp $CONTAINER:/var/www/html/wp-content $BACKUP_DIR/wp-content-tmp

# 2. Replace old backup with new
rm -rf $BACKUP_DIR/wp-content
mv $BACKUP_DIR/wp-content-tmp $BACKUP_DIR/wp-content

cd $BACKUP_DIR

# 3. .gitignore uploads if you want (optional)
if [ ! -f .gitignore ]; then
  echo "wp-content/uploads/" > .gitignore
fi

# 4. Check for changes
if git status --porcelain | grep .; then
  git add wp-content
  git commit -m "Automated backup: $(date '+%Y-%m-%d %H:%M')"
  git push origin $GIT_BRANCH
  echo "Backup committed and pushed."
else
  echo "No changes detected. No backup needed."
fi