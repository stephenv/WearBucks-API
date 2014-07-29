WearBucks API
=============

API for "WearBucks (Pay for Starbucks)" application on Google Play (Android)


HTTP RESTful API for retrieving Starbucks user information

Dependencies
** Slim PHP Microframework **
** Google Analytics (optional) **
** MongoDB (optional) **

API Endpoints
**<code>POST</code> /account **
**<code>POST</code> /locations (In progress) **


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
