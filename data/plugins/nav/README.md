ナビゲーションカラム用プラグインについて

plugins/nav/
（つまりこのファイルが有るフォルダ）に、プラグインをおいてください。

プラグインの定義
plugin.ini
を持つフォルダ(名前は自由)
設定可能なプラグインの場合はconfigフォルダも必要です

plugin.iniは次のように書いてください。

page=実際に表示するファイル名(index.phpなど)
name=プラグインの名前
desc=プラグインの説明
config=設定用ページのファイル名(設定が必要なら)

configフォルダの位置は/data/plugins/config/nav/プラグインのフォルダ名
です。
configフォルダに次のようなファイルを置いてください

1-conf.ini
2-conf.ini
3-conf.ini
.
.
.

*-conf.iniは次のように書いてください。これ以外になにか書いても良いです

name=設定の名前
desc=設定の説明