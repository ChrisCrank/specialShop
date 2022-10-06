FROM node:14.20.1

# create destination directory
RUN mkdir -p /var/www/app
WORKDIR /var/www/app

# update and install dependency
RUN apt update && apt upgrade


# copy the app, note .dockerignore
COPY ./Frontend /var/www/app
#RUN npm install

# build necessary, even if no static files are needed,
# since it builds the server as well
#RUN npm run build


# expose 5000 on container
EXPOSE 5000

# set app serving to permissive / assigned
ENV NUXT_HOST=0.0.0.0
# set app port
ENV NUXT_PORT=5000
RUN npm install
#CMD [ "npm", "run", "install" ]
#CMD [ "npm", "run", "build" ]
