all:
	docker build -t oauth .
	docker run -d -p 8000:8000 -v "C:\xampp\htdocs\oauth":/var/www/html --name oauthApp oauth
create:
	docker build -t oauth .
run:
	docker run -d -p 8000:8000 -v "C:\xampp\htdocs\oauth":/var/www/html --name oauthApp oauth
stop:
	docker stop oauthApp
del:
	docker rm oauthApp
	docker rmi oauth