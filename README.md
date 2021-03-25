<h1 align="center">会员分享</h1>

## 安装
```shell
$ composer require jncinet/laravel-share
```

## 配置
```shell
$ php artisan vendor:publish --provider="Jncinet\\LaravelShare\\ShareServiceProvider"
```

## 使用
### 会员模型添加
```php
// ...
use Jncinet\LaravelShare\Traits\Sharer;

class User extends Authenticatable
{
    use Sharer;
    
    // ...
}
```

### 用户API
```php
$user = User::find(1);
$article = Article::find(1);
// 分享文章
$user->share($article);
// 删除分享文章
$user->unShare($article);
// 获取所有分享的文章
$user->getShareItems(Article::class)
// 会员是否分享了文章
$user->hasShared($article); 
```

### 内容模型添加
```php
// ...
use Jncinet\LaravelShare\Traits\Shareable;

class Article extends Model
{
    use Shareable;
    
    // ...
}
```

### 内容API
```php
$user = User::find(1);
$article = Article::find(1);
// 内容是否被用户分享过
$article->isSharedBy($user);
// 文章分享记录
$article->shares;
// 分享过内容的会员
$article->sharers;
```