#!/bin/bash

# Initially created with ChatGPT - Code Copilot
# https://chatgpt.com/g/g-2DQzU5UZl-code-copilot
# Prompt: Write a bash script to find all uncompressed TIFF files and compress them.

# Define the compression method (LZW is a lossless compression for TIFF)
COMPRESSION="LZW"
DATA_DIR=${1:-"../data"}

# Function to compress a TIFF file
compress_tiff() {
	local file="$1"
	local temp_file="${file%.tif}_compressed.tif"

	# Compress the TIFF file
	convert "$file" -compress "$COMPRESSION" "$temp_file"

	# Check if compression was successful, then replace original
	if [ $? -eq 0 ]; then
		mv "$temp_file" "$file"
		echo "Compressed: $file"
	else
		echo "Failed to compress: $file"
		rm -f "$temp_file" # Clean up temporary file if compression failed
	fi
}

# Find all TIFF files (both .tif and .tiff)
find "$DATA_DIR" -type f \( -iname "*.tif" -o -iname "*.tiff" \) | while read -r file; do
	# Check if the file is already compressed
	compression_type=$(identify -format "%[compression]" "$file")

	if [ "$compression_type" != "$COMPRESSION" ]; then
		compress_tiff "$file"
	fi
done
