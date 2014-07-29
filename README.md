WearBucks API
=============

#### About:
WearBucks API is a RESTful HTTP API for retrieving Starbucks user information. This is the API used by **WearBucks (Pay for Starbucks)** application on Google Play (Android) coming soon. The API is based off [Starbucks class](https://github.com/Neal/php-starbucks) by [Neil](https://github.com/Neal). _Login information (username and password) is not stored and the API should only be used over SSL/TSL_

#### Dependencies:
- Slim PHP Microframework
- Google Analytics **(optional for usage statistics)**
- MongoDB **(optional for geolocation)** 
- PHP
- JSON

#### API Endpoints
- **<code>POST</code> /account**
- **<code>POST</code> /locations (In progress)**

## <code>POST</code> /account
> Retrieves Starbucks.com account details (given correct credentials) 

##### Parameters
| Name  | Description | Details |
| ------------- | ------------- | ------------- |
| username  | Starbucks username or password  | required |
| password  | Starbucks password  | required | 
##### Headers
| Name  | Description | Details |
| ------------- | ------------- | ------------- |
| Content-Type  | application/json  | required |
| password  | Starbucks password  | required |

##### Example JSON Request body:
```json
{
  "username": "user@domain.com",
  "password": "******"
}
```
##### Example JSON Response body:
```json
{
    "error": false,
    "customer_name": "Firstname Lastname",
    "stars": "0",
    "rewards": "0",
    "dollar_balance": "00.00",
}
```
##### Response codes & Error Handling
> The API will always return an HTTP status code 200 however there may be errors. In the JSON response body, "error" will not be false. Example:

```json
{
  "error":{
    "code": 401,
    "message": "Unauthorized",
    "description": "Authentication credentials were malformed or incorrect."
  }
}
```

Error codes
- <code>400</code> Bad Request - Likely that the request was not formatted correctly
- <code>401</code> Unauthorized - Likely that the credentials were incorrect



## <code>POST</code> /locations
>**<code>Note:</code> This API endpoint is in progress and therefore the documentation is not complete. Analytics is also currently not supported**
This feature  returns a list of nearby Starbucks locations stored in a MongoDB database (not included in API) 

set the following environment variables in index.php: 
```php
$_ENV['LOCATIONS'] = true;
$_ENV['MONGOHQ_URL'] = mongodb://user:pass@server.mongohq.com/db_name;
```

/locations
Content-Type: application/json

Example JSON body:
```json
{
  "latitude": 00.0000000,
  "longitude": -00.0000000
}
```

## Google Analytics 
Google analytics can be enabled and is tracked using "Measurement Protocol". Ensure "Universal Analytics" is enabled on the property and use the provided tracking ID "UA-XXXX-XX"

