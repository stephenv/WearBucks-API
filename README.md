WearBucks API
=============

API for "WearBucks (Pay for Starbucks)" application on Google Play (Android)


HTTP RESTful API for retrieving Starbucks user information. 
IMPORTANT: Login information (username and password) is not stored by the API. T

The API based off of

## Dependencies
- Slim PHP Microframework
- Google Analytics **(optional for usage statistics)**
- MongoDB **(optional for geolocation)** 


## API Endpoints
- **<code>POST</code> /account**
- **<code>POST</code> /locations (In progress)**

## <code>POST</code> /account

| Name  | Description | Details |
| ------------- | ------------- | ------------- |
| username  | Starbucks username or password  | required |
| password  | Starbucks password  | required |

Content-Type: application/json

Example JSON body:
```json
{
  "username": "user@domain.com",
  "password": "******"
}
```
Response
```json
{
    "error": false,
    "customer_name": "Firstname Lastname",
    "stars": "0",
    "rewards": "0",
    "dollar_balance": "00.00",
}
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
