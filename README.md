API Documentation
Endpoints
_List Products_

`GET /api/products/{type}`

Parameters:

type (path): fruit or vegetable
unit (query): g or kg
minQuantity (query, optional): Minimum quantity filter
maxQuantity (query, optional): Maximum quantity filter

_Create Products_

`POST /api/products`

Request Body:
[
    {
        "name": "Apple",
        "quantity": 500,
        "unit": "g",
        "type": "fruit"
    }
]

_Delete Product_

`DELETE /api/products/{type}/{id}`

Parameters:

type (path): Product type
id (path): Product ID