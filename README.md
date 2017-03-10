# CouchDB / PouchDB Tech demo

Super simple tech demo for CouchDB together with PouchDB. Very much inspired by the official PouchDB demo.
Use for demo purpose only... :-)

## To start
1. Clone repository
2. Run the following command
```
cd couchdb-docker
docker build -t test/couchdb .
docker run -d -p 5984:5984 test/couchdb
cd ..
curl -X PUT http://127.0.0.1:5984/_config/httpd/enable_cors -d '"true"'
curl -X PUT http://127.0.0.1:5984/_config/cors/origins -d '"*"'
curl -X PUT http://127.0.0.1:5984/_config/cors/credentials -d '"true"'
curl -X PUT http://127.0.0.1:5984/_config/cors/methods -d '"GET, PUT, POST, HEAD, DELETE"'
curl -X PUT http://127.0.0.1:5984/_config/cors/headers -d '"accept, authorization, content-type, origin, referer, x-csrf-token"'
cd pouchdb-client
docker run -d -p 5984:5984 test/couchdb
```
3. Go to http://localhost:8000/ in two different tabs.
4. Success!
