WearBucks API
=============

API for "WearBucks (Pay for Starbucks)" application on Google Play (Android)


HTTP RESTful API for retrieving Starbucks user information

API Endpoints
/account 
/locations (In progress)

Content-Type: application/json

Example JSON body:
```json
{
  "username": "example@user.com",
  "password": "******"
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
