## 開發環境

### 1. 環境變數檔配置

  ```sh
  cp .env.sample .env
  ```

  * 請編輯 .env 檔案內容以符合自有開發環境，請確實掌握每個環境變數的意義。

### 2. 建構 Docker Image

  ```sh
  docker build --rm -t attendance .
  ```

### 3. 啟動本地開發環境

  ```sh
  make up
  ```

### 4. 重啟容器

  1. 執行 `make down` 清除容器與橋接器
  2. 執行 `make up` 重新啟動容器
  3. :tada:

### 附錄

:bangbang: 若沒有使用nginx-proxy, 請將docker-compose 改為port-mapping模式 :bangbang: 

#### 常用指令

  指令                                           | 描述
  ----                                           | ----
  `make reload`                                  | reload lumen
  `make logs`                                    | show docker-compose logs

