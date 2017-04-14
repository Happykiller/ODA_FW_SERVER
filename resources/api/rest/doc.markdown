# Routes

## User

* `/user/pwd/`
    * type : PUT
    * mandatory params : "userCode","pwd","email"
    * public : true

## Session

* `/session/:key`
    * type : GET
    * public : true

## Avatar

* `/avatar/:codeUser`
    * type : GET
    * public : true
    * optional params : "w","h"