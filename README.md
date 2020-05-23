wp-create_pages
===
WordPressでWebサイト作成時の固定ページ登録をjsonファイルをもとに実行する

## Overview
- 固定ページの追加
- 固有テンプレートファイルの追加

## How to use
1. wordpress のルートディレクトリへ `wp-create_pages.php` と `sitemap.json` を配置
2. 作成したい固定ページに合わせ `sitemap.json` を編集
3. `wp-create_pages.php` を実行する `http://localhost/wp-create_pages.php` などへのアクセスでブラウザから実行も可
4. `wp-create_pages.php` と `sitemap.json` を削除

## Appendix
### sitemap.json Example
``` json
{
  "page": [
    {
      "title": "お問い合せ",
      "slug": "contact"
    }, {
      "title": "会社案内",
      "slug": "company",
      "children": [
          {
            "title": "会社概要",
            "slug": "about"
          }
      ]
    }
  ]
}
```
