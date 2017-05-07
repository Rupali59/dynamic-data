# dynamic-data
This repo allows users to dynamically create databases. It consists of the following API:-
1. POST http://{url}/api/createSchema
  INPUT: 
  {  
   "tableName":"TABLENAME",
   "columns":[  
      {  
         "name":"first_name",
         "type":"char",
         "arguments":["20"],
         "unique":true
      },
      {  
         "name":"last_name",
         "type":"char",
         "arguments":["20"]
      },
      {  
         "name":"address",
         "type":"varchar",
         "arguments":["200"]
      },
      {  
         "name":"pincode",
         "type":"integer",
         "default":"323131"
      }
   ]
}
2. GET http://{url}/api/listSchema

3. DELETE http://{url}/api/{schemaname}/dropSchema

4. POST http://{url}/api/{schemaname}/add-data
INPUT:
{  
  "first_name" : "ABC",
  "last_name" : "XYZ",
   "address" : "abcd efgh ijkl mnop",
    "pincode" : 564321
}

5. GET http://{url}/api/{schemaname}/get-data

6. POST http://{url}/api/{schemaname}/update-data
INPUT:
{  
   "id":1,
   "content":{  
      "first_name" : "ABCDEF",
      "last_name" : "XYZ123",
      "address" : "abcd efgh ijkl mnop qrst",
      "pincode" : 698756
   }
}

7. DELETE http://{url}/api/{schemaname}/delete-data
