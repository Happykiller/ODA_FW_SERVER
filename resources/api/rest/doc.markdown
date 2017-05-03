# Routes

## Avatar

* `/avatar/:codeUser`
    * type : GET
    * public : false
    * optional params : "w","h"
    
## Message

* `/message/`
    * type : POST
    * mandatory params : "userId","message","level","expirationDate","rankId"
    * public : false

* `/message/`
    * type : GET
    * public : false

* `/message/current`
    * type : GET
    * public : false

* `/message/read/:messageId`
    * type : PUT
    * public : false    

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

* `/navigation/right/`
    * type : GET
    * public : false

* `/navigation/right/:id`
    * type : PUT
    * public : false
    * mandatory params : "value"

## Rank

* `/rank/`
    * type : GET
    * public : false

## Session

* `/session/:key`
    * type : GET
    * public : false

## User

* `/user/`
    * type : GET
    * public : false

* `/message/`
    * type : POST
    * mandatory params : "firstName","lastName","mail","password","userCode"
    * public : true

* `/user/current`
    * type : GET
    * public : false

* `/user/:userCode`
    * type : GET
    * public : false

* `/user/:userCode`
    * type : PUT
    * mandatory params : "mail","active","rankId","desc"
    * public : false

* `/user/pwd/`
    * type : PUT
    * mandatory params : "userCode","pwd","email"
    * public : false

* `/user/mail/`
    * type : GET
    * public : false

* `/user/search/mail/`
    * type : GET
    * mandatory params : "email"
    * public : true