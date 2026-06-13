# Runbooks

## Falha de login

- confirme `usuarios` no banco e se houve bootstrap controlado por ambiente;
- valide `DB_*` e a conectividade com o MySQL;
- verifique logs PHP/Nginx do container.

## Upload quebrado

- confirme escrita em `uploads/`;
- verifique se o volume `galeria_uploads` está montado;
- valide extensão permitida da imagem.

## Erro após deploy

- confira `docker service logs galeria-fred_app -f`;
- confira `docker service logs galeria-fred_mysql -f`;
- valide se `galeria-fred.env` contém todas as variáveis exigidas.