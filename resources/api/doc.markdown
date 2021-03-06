# Routes

* When an interface is "private" you need to provide the token.


## Avatar

* `/avatar/:codeUser`
    * type: GET
    * public: false
    * optional params: "w","h"


## Message

* `/message/`
    * type: POST
    * mandatory params: "userId","message","level","expirationDate","rankId"
    * public: false

* `/message/`
    * type: GET
    * public: false

* `/message/current`
    * type: GET
    * public: false

* `/message/read/:messageId`
    * type: PUT
    * public: false    


## Navigation

* `/navigation/page/`
    * type: GET
    * public: false

* `/navigation/rank/`
    * type: GET
    * public: false
    * desc: Get list of rank (ex: admin, user, etc)

* `/navigation/rights/`
    * type: GET
    * public: false
    * desc: Get the rights available on the platform

* `/navigation/right/`
    * type: GET
    * public: false
    * desc: Get the rights for the current user

* `/navigation/right/:id`
    * type: PUT
    * public: false
    * mandatory params: "value"


## Rank

* `/rank/`
    * type: GET
    * public: false


## Session

* `/session/`
    * type: POST
    * public: false
    * mandatory params: "userCode","password"

* `/session/:key`
    * type: GET
    * public: false

* `/session/check`
    * type: GET
    * public: false
    * mandatory params: "code_user","key"

* `/session/:key`
    * type: DELETE
    * public: false


## System

* `/sys/page/trace`
    * type: POST
    * public: false

* `/sys/report/page/activity`
    * type: GET
    * public: false

* `/sys/theme/`
    * type: GET
    * public: false

* `/sys/cleanDb/`
    * type: POST
    * public: false
    * mandatory params: "exec"

* `/sys/report/interfaceMetric`
    * type: GET
    * public: false

* `/sys/log/`
    * type: POST
    * public: false
    * mandatory params: "type","msg"

* `/sys/param/:key`
    * type: GET
    * public: true

* `/sys/param/:key`
    * type: PUT
    * public: false
    * mandatory params: "value"
    

## User

* `/user/`
    * type: GET
    * public: false

* `/message/`
    * type: POST
    * mandatory params: "firstName","lastName","mail","password","userCode"
    * public: true

* `/user/current`
    * type: GET
    * public: false

* `/user/:userCode`
    * type: GET
    * public: false

* `/user/:userCode`
    * type: PUT
    * mandatory params: "mail","active","rankId","desc"
    * public: false

* `/user/current`
    * type: PUT
    * mandatory params: "password","field","value"
    * public: false

* `/user/pwd/`
    * type: PUT
    * mandatory params: "userCode","pwd","email"
    * public: false

* `/user/mail/`
    * type: GET
    * public: false

* `/user/search/mail/`
    * type: GET
    * mandatory params: "email"
    * public: true

* `/user/report/activity`
    * type: GET
    * public: false