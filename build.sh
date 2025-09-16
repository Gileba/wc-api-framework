#!/bin/bash

# Build script for WC API Framework
# This script increments the build number and creates a zip file

# Get current build number from plugin header
CURRENT_BUILD=$(grep "\* Version:" wc-api-framework.php | sed "s/.*(build \([0-9]*\)).*/\1/")

# Increment build number
NEW_BUILD=$((CURRENT_BUILD + 1))

echo "Current build: $CURRENT_BUILD"
echo "New build: $NEW_BUILD"

# Update build number in main file
sed -i '' "s/define('WC_API_FRAMEWORK_BUILD', '[^']*');/define('WC_API_FRAMEWORK_BUILD', '$NEW_BUILD');/" wc-api-framework.php

# Update build number in plugin header
sed -i '' "s/\* Version: 1\.0\.0-alpha\.1 (build [0-9]*)/\* Version: 1.0.0-alpha.1 (build $NEW_BUILD)/" wc-api-framework.php

# Remove old zip if it exists
rm -f wc-api-framework.zip

# Create new zip
zip -r wc-api-framework.zip . -x "*.DS_Store" "*/.*" ".git/*" "build.sh"

echo "Created wc-api-framework.zip with build $NEW_BUILD"
