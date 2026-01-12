#!/bin/bash

echo "========================================="
echo "  QUICK PUSH TO GITHUB"
echo "========================================="
echo ""

# Add all changes
echo "📦 Adding all changes..."
git add .

# Commit with timestamp
echo "💾 Committing changes..."
COMMIT_MSG="Update: $(date '+%Y-%m-%d %H:%M') - Add verification system, fix prestasi poin, complete documentation"
git commit -m "$COMMIT_MSG"

# Push to main
echo "🚀 Pushing to GitHub..."
git push origin main || git push origin master

echo ""
echo "✅ Done! Check your GitHub repository."
echo "========================================="
