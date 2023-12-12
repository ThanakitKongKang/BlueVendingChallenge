# Stage 1: Build the React application
FROM node:latest as build

WORKDIR /usr/src/app

COPY package.json yarn.lock ./
RUN yarn install --frozen-lockfile --silent
COPY . .
RUN yarn build

# Stage 2: Serve the built application using Nginx
FROM nginx:latest

COPY --from=build /usr/src/app/build /usr/share/nginx/html

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
