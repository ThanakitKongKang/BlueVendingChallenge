# Use Node.js as the base image
FROM node:latest

# Set working directory
WORKDIR /usr/src/app

# Copy package.json and yarn.lock to the working directory
COPY package.json yarn.lock ./

# Add `/app/node_modules/.bin` to $PATH
ENV PATH /app/node_modules/.bin:$PATH

# Install dependencies using Yarn
RUN yarn install --silent

# Copy the entire app
COPY . .

# Set the command to start the React app
CMD ["yarn", "start"]
