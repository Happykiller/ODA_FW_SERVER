# Routes

## User

* `/user/pwd/`
    * type : PUT
    * mandatory params : "userCode","pwd","email"
    * public : false

## Session

* `/session/:key`
    * type : GET
    * public : false

## Avatar

* `/avatar/:codeUser`
    * type : GET
    * public : false
    * optional params : "w","h"

## Navigation

* `/navigation/page/`
    * type : GET
    * public : false

* `/navigation/rank/`
    * type : GET
    * public : false

* `/navigation/rights/`
    * type : GET
    * public : false
    * type : GET
    * public : false

* `/navigation/right/:id`
    * type : PUT
    * public : false
    * mandatory params : "value"