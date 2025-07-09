#!/bin/bash

# Prompt for user input for the two tags
# This script tags the images based on user input and pushes them to the appropriate Docker repository.

echo "Enter tag for samlwrapper-onelogin (e.g., dfd9bb7cdf21):"
read UI_TAG
#echo "Enter tag for samlwrapper-glu-app (e.g., ed9129e7d204):"
#read APP_TAG

# Define the image names
UI_IMAGE="dangtue2020/samlwrapper-onelogin"
#APP_IMAGE="dangtue2020/samlwrapper-glu-app"

# Tag the Docker images
echo "Tagging images..."
docker tag $UI_TAG $UI_IMAGE:$UI_TAG
#docker tag $APP_TAG $APP_IMAGE:$APP_TAG

# List the Docker images
docker images

# Push the tagged images to the Docker repository
echo "Pushing images..."
docker push $UI_IMAGE:$UI_TAG
#docker push $APP_IMAGE:$APP_TAG

echo "Docker images tagged and pushed successfully."
