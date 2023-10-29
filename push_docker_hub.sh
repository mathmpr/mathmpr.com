PROJECT_ZIP_FILE=./mathmpr.com.zip
COMPOSER_CACHE=~/.cache/composer
if [ ! -f "$PROJECT_ZIP_FILE" ]; then
    echo "comprimindo o projeto..."
    zip -r $PROJECT_ZIP_FILE ./ -x push_docker_hub.sh -x mathmpt.com.zip -x composer_cache.zip > /dev/null
fi
echo "verificando se existe cache do composer no host..."
if [ -d "$COMPOSER_CACHE" ]; then
    echo "comprimindo o cache do composer..."
    zip -r composer_cache.zip -x push_docker_hub.sh -x mathmpt.com.zip -x composer_cache.zip ./ > /dev/null
fi
echo "docker-compose down..."
docker-compose down
echo "fazendo a build dos containers..."
docker-compose build --no-cache
echo "deletando a tag latest..."
docker image rm mathmpr/mathmpr:latest > /dev/null
echo "criando a tag latest..."
docker tag mathmpr mathmpr/mathmpr:latest > /dev/null
docker login
echo "fazendo push no docker hub..."
docker push mathmpr/mathmpr:latest
echo "todas as operações foram executadas com sucesso até aqui [fim da execução]..."
