# SETUP
- go to the `_DOCKER` folder and run / install the Container with `docker-compose up -d`
- if you want to see the Dev Version of the Storefront go to the `Frontend` folder and run `yarn install && ỳarn run dev`
- The Databse should already be setup, if you want you can always go to the Default there is a export.sql inside the `_DOCKER/DatabaseBackup` Folder.

# configuration
- you can configure the Ports using the `_DOCKER/.env` file 
- if you adjust the Backend Port please also adjust them inside the `Frontend/.env` file
- if you change the Database Ports / names / credintinals please also adjust them inside the `Backend/.env` file ( DATABASE_URL )
- All Configuration for the Backend (like Stateless Authentification, img Upload Settings, Language Settings) can be done inside the `Backend/src/Cofnig/StaticConfigs.php` file

# first Steps
- First open the Storefront (`loclhost:3000`(dev) or `localhost:5000`(prod)) and create a normal Account 
  - The Storefront is in docker network host mode, that means it ignores the port mapping and exposes its ports directly to the Localhost of the Docker-hoster (this is because we want to test the prod. mode in local enviroment) 
- If you want to have Admin Access for adding Products and Categories please set the `is_admin` flag inside the Database (user) to true.
  - You can access the Database by default with `localhost:83` user: `admin` password: `myPassword`
  - You can access the Backend Overview by clicking on the Top Right "Hammer" Icon if you are logged in as Admin
  - If you create/edit a Product you can Upload Images inside the Description if you click the 3 Dots on the top right of the Editor.
- You can use also an existing account 
  - normal Account email: `user@user.user` password: `useruser`
  - admin Account email: `admin@admin.admin` password: `adminadmin`

# Functionality
## Cart
- You can put Items inside your Cart. 
- The Cart will Check if the Quantity exceeds the existing stock of the product, if so an error-message is displayed
- There are 2 types of Cart, a Cookie Cart (for users who are not logged in), and a Cart which is direktly saved inside the Database. 
- Your Cards will merge if you have a cookie Cart inside the LogIn process
## Order
- You Can Order Products, because this is a demo Project there is no real Payment connected 
- The Project will check for the Stock of the Product and it will adjust the Stock of the Product if you Order a Product
## Product Detail 
- Products can have Custom HTML, inside the Backend you have the option to Upload Pictures via the HTML Input. 
If you do this the Pictures are processed and saved inside e tmp folder. If the Products is saved with the Image inside the HTML field the Image will be saved inside the product folder
## Category Page
- it has a working Pagination (see Cars Category), but besides that there is nothing special as well
## Images
- every image which is uploaded to the page (html input or thumbnail) is processed and saved in different sizes. the Storefront takes care about the correct Img-Srcset
- every Image is uploaded with a tratemark located in `Backend/public/img/uploads/water.png` 
## Multi Language
- You can switch the Language, the Language is alsow saved inside the Database if you log in your Language is automaticly set. 
- Products/Categories are fetched based on your supplier Language (which isset via the header)
## Meta  
- on PDP/PLP the App takes care about the Meta Description / connical urls
## SEO URlS
- Category Pages and Product Pages can be accessed using the Name of the Product (language based)
# Some Notes
- i needed about 6 hours x 7 days for this project.
- this was the first time for me to work with standalone Symfony
- because there is no Email Delivery on localhost the Activation Codes/Forgot Password Codes are comming directly from the request as response.
- Im using only doctrine 2 for the Database connections but im not a big fan of doctrine 2 because it fetches to much data in the background. 
- i know that there are some missing realtions inside the Entity Setup
  - The address of the User is directly saved inside the user Object. Normaly there should be a `user.shippingAddressId` and a `user.billingAddressId` referencing on a userAddresses Table.
  - The Country of the User is directly saved not as countryId
  - Products dont have there own taxrate at the moment everything is hardcoded to 19% tax
  - Orders are saving there OrderItems inside a JSON Object instead of an OrderItems Table which is referencing the OrderId per row (ManyToOne)
  - There is no ShippingMethod/PaymentMethod Table so there is a Varchar field of shippingMethod and paymentMethod inside the OrderEntity instead of an referencing ID
  - There is no Media Manager which results in same Images existing multiple Time on the Disc 
  - and so on... 
- this is because this is just a demo. This project isnt made for prod. mode.
