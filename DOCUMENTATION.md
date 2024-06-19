# Installazione

Ho provato a utilizzare docker per consentire il testing e l'installazione di questa repository.\
Purtroppo, avendo un computer Windows con WSL sopra, docker funziona molto lentamente con i container basati su PHP e non sono riuscito in alcun modo a sistemarlo.\
In ogni caso ho lasciato tutti i file nel caso fosse necessario provare. Lascio tutte le istruzioni [qua](#installazione-con-docker).

Mentre invece, per un'installazione più tradizionale di Laravel lascio [di seguito le istruzioni](#installazione-tradizionale).

> [!IMPORTANT]
> Che venga fatta la configurazione "tradizionale" o quella tramite docker.\
> Sarà necessario configurare (una volta conosciuto) il parametro 'APP_URL' del file .env,\
> Completo di porta se possibile, per poter fare in modo che alcuni ritorni dei punti API siano corretti,

Subito dopo i processi di installazione ho lasciato [qualche riga](#utilizzare-il-servizio) per permettere di verificare e di lavorare con i punti API.


## Installazione "tradizionale"

> [!WARNING]
> Per questa configurazione sono necessarie le seguenti librerie sulla propria macchina host:
> - PHP 8.1+
> - MySQL
> - Composer

Dopo aver scaricato la repository da github sarà necessario lanciare due comandi e fare un paio di configurazioni per poter iniziare a lavorare.


Il primo comando sarà quello per installare i pacchetti tramite composer
```bash
composer install
```

Successivamente, sarà necessario creare il file .env
```bash
cp .env.example .env
```
E configurare i parametri di connessione al database di MySQL (o qualunque altro tipo di database preferito).

Per ultimo basterà lanciare il comando:
```bash
php artisan serve
```
Per permettere lo start di un server locale tramite cui collegarsi.


Da quello che mi ricordo per far partire un installazione con Laravel dovrebbero bastare questi comandi.\
Per qualunque necessità potete contattarmi.


## Installazione con Docker

> [!WARNING]
> Sulla propria macchina basterà avere a disposizione DOCKER con il plugin COMPOSE


Una volta scaricata la repository serviranno alcuni comandi.
```bash
docker compose run --rm composer install
```
Questo comando farà partire un container di servizio che serve solo per poter lavorare con composer\
e permetterà di installare i pacchetti della cartella vendor.

Successivamente, sarà necessario creare il file .env
```bash
cp .env.example .env
```
E configurare i parametri di connessione al container di MySQL.\
In questo caso i parametri saranno visionabili e modificabili [nel file](./env/mysql.env)

Infine:
```bash
docker compose up -d server php mysql 
```
Permetterà di far partire i servizi essenziali al funzionamento, cioè:
- NGINX
- PHP
- MySQL


# Utilizzare il servizio

> [!NOTE]
> Da questo momento in poi fornirò i comandi per entrambe le casistiche, in quanto la logica di funzionamento è la stessa

Prima di tutto ho fornito un paio di chiamate di esempio tramite Postman, disponibili [al file](./Postman/OpenLibraryAPI.postman_collection.json)

Dopo aver utilizzato le chiamate API di:
```bash
GET /search
POST /review
```

Sarà necessario far partire lo schedulatore di Laravel che lavora i Job asincroni.
Il comando sarà:
```bash
//DOCKER
docker compose run --rm artisan queue:work

//DIRETTO
php artisan queue:work
```

Una volta fatto ciò, se la lavorazione è andata a buon fine, sarà possibile recuperare le informazioni acquisite tramite l'endpoint:
```bash
GET /review/{reviewID}
```
A questo punto verranno consegnate anche le informazioni per recuperare i dati riguardanti Autori e Work. Precisamente dall'endpoint:
```bash
GET /author/{authorID}
GET /work/{workID}
```

Se, invece, si desidera far partire gli Unit test sarà possibile lanciare uno dei due comandi, in base a quanto è stato configurato.
```bash
//DOCKER
docker compose run --rm artisan test

//DIRETTO
php artisan test
```

