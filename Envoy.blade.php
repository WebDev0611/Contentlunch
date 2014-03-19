@servers(['web' => 'webdeploy@10.254.2.21'])

@task('deploy')
	cd /www/contentlaunch
	git pull origin master
  npm update
  php composer.phar update
  bower update
  php artisan migrate --env="test"
  phpunit
  gulp
@endtask
