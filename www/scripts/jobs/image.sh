#!/bin/bash

# 72時間より古い画像の削除
# root で設定 改行コードはLFで

/usr/sbin/tmpwatch 72 /var/BudoriNeri/public/media/
