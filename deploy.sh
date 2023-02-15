#!/usr/bin/env bash
DEPLOY_OPTION=${1:-all}
MASTER_PATH=/www/wwwroot/www.admin9.com

redEcho(){
   echo -e "[ $1 ]"
}

deployProd(){
  source /etc/profile

  redEcho "开始部署"
  echo $(date "+%Y-%m-%d %H:%M:%S")

  cd ${MASTER_PATH} || exit
  redEcho '进入网站目录'
  pwd

  redEcho 'git reset & clean'
  git reset --hard
  git clean -df

  redEcho '拉代码 git pull'
  git pull origin develop
  redEcho 'git 最近一次日志'
  git log --pretty=format:"%h %cd %cr %s (%cn)" -1  | xargs -0 echo

  if [ "$1" != 'frontend' ]; then
       redEcho '清理 opcache'
       php artisan deploy:opcache clear

       redEcho '安装 composer 依赖'
       composer install --optimize-autoloader --no-dev

       redEcho '重置 route & config 缓存'
       php artisan optimize

       redEcho '执行数据库迁移'
       php artisan migrate --force

#       redEcho 'horizon:publish'
#       php artisan horizon:publish

       redEcho '重启队列'
       php artisan horizon:terminate
  fi

  #  if [ "$1" != 'backend'  ]; then
  #       redEcho '进入前端目录'
  #       cd frontend || exit
  #       pwd
  #
  #       redEcho '前端构建'
  #       yarn install
  #       yarn run build:prod
  #  fi

  redEcho '完成'
}


if [[ -d ${MASTER_PATH} ]]; then
  deployProd "${DEPLOY_OPTION}"
fi
