git add .
git commit -m "Ajustado valor da consulta de CNPJ de R$ 0.05 para R$ 0.20"
git push origin main


# Entrar no diretório do projeto
cd /home/govnex/htdocs/govnex.site/govnex

# Puxar as últimas alterações do GitHub
git pull origin main

# Recarregar Nginx se houver alterações de configuração
systemctl reload nginx