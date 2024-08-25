# Nette Api Project

- PHP 8.2
- Nette 3.2
- PostgresSQL 16.3

## Instalace
Naklonovat github repozitář
```bash
git clone https://github.com/t-ror/nette-project-api.git
```

Příkaz pro instalaci. Po instalaci by měla apka běžet na http://localhost/
```bash
make install
```

## Dokumentace API
Po úspěšně instalaci je automaticky vygenerovaná dokumentace na adrese http://localhost/.
Mělo by jít skrze to i přímo testovat.

Pro vygenerování do souboru lze využít command
```bash
make api-schema
```

## Komentáře
- "`price (float 10,2)` - cena produktu" jsem upravil
  - ukládám jako decimal 10,2
  - dále s tím pracuju jako string pomocí knihovny `phpmoney/money` a obalil si vlastní třídou `Price`
  - není bezpečné pracovat s něčím kritickým jako je cena pomocí floatu
- `created_at` a `updated_at`
  - pracuji s nimi jako `DateTimeImmutable`
  - je to za mě bezpečnější způsob, datumy se často předávájí a dělají se nad nimi různé výpočty a kolikrát už mi obyčejné `DateTime` věci zkomplikovalo
- nad produkt jsem přidal `deleted_at`
  - něco jako produkt by měl v reálném projektu příliš mnoho vazeb
  - úplné smazání by bylo příliš komplikované nebo nemožné
  - pomocí `deleted_at` pak všechny endpointy dané produkty odfiltrují "jako by nebyly"
  - endoint `delete` má nepovinný parametr `force`, který produkt opravdu natvrdo smaže z databáze
    - pro takto mini projekt tam takový parametr může být, může se hodit při testování
- rozšíření aplikace
  - [x] "zabezpečení API"
    - jednoduché pomocí `Bearer Token`
    - klíč je `4d04a3db45bc715908d4d2ec7aa78a5c0126c22ecb7b17eb4fd695b5075b4f85`
    - pro silnější zabezpečení by šlo využít `OAuth2`
  - [x] "návrh verzování API"
    - verze API se předává v URL
  - [x] "filtrace záznamů a stránkování při GET requestech"
    - endpoint `list` má filtry a stránkování
  - [x] "možnosti dokumentace API (například automatické generování dokumentace)"
    - dokumentace je automaticky vygenerovaná na homepage http://localhost/ pomocí Swagger
    - aby se změny projevili je jen nutné po úpravách smazat cache (`make rmcahe`)
  - [ ] "testy"
    - nevypracoval jsem
    - využil bych Codeception, mám s ním nejvíce zkušeností a nabízí široké možnosti testování
    - pro test mapování, authentizace apod. by šli využít unit testy
    - přes Codeception lze napsat i přímo [testování API](https://codeception.com/docs/APITesting)

## Příkazy
Pustí docker container
```bash
make up
```

Vypne docker container
```bash
make down
```

Restartuje docker container
```bash
make restart
```

Vstoupi do docker container
```bash
make exec
```

Vymaže cache
```bash
make rmcache
```

Pustí stack statické analýzy
```bash
make ci
```

CodeSniffer - codestyle and typehints
```bash
make cs
```

PhpStan - PHP Static Analysis
```bash
make phpstan
```

Zkontroluje mapování entit
```bash
make test-entity
```

Vytvoří `diff.sql` soubor s rozdílem mezi ORM a databází
```bash
make db-diff
```