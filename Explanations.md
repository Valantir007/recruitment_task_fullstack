## Uwagi do zadania:

1. plik `package-lock.json` umieściłbym w repozytorium aby wszyscy przechodzący to zadanie pracowali na tych samych paczkach.
2. Wersja symfony użyta w projekcie straciła wsparcie - warto by było podnieść do może najnowszej wersji LTS

```
bin/console about
```

Symfony
 --- 
Version                    4.4.50                            
Long-Term Support          Yes                               
End of maintenance         11/2022 Expired                   
End of life                11/2023 Expired
 --- 

3. Sam plik SetupCheck.js posiada błąd. Mowa o tym kawałku kodu

```
const baseUrl = this.getBaseUrl();
    axios.get(baseUrl + `/api/setup-check?testParam=1`).then(response => {
    let responseIsOK = response.data && response.data.testParam === 1
    this.setState({ setupCheck: responseIsOK, loading: false})
}).catch(function (error) {
    console.error(error);
    this.setState({ setupCheck: false, loading: false});
});
```

Jeśli serwer zwróci błąd, np. status 500, to przeglądarka rzuci błędem:

`Uncaught (in promise) TypeError: Cannot read properties of undefined (reading 'setState')`

Dzieje się tak, ponieważ context `this` został zmieniony. Poprawka jest dość prosta:

```
axios.get(baseUrl + `/api/setup-check?testParam=1`).then(response => {
    let responseIsOK = response.data && response.data.testParam === 1
    this.setState({ setupCheck: responseIsOK, loading: false})
}).catch(error => {
    console.error(error);
    this.setState({ setupCheck: false, loading: false});
});
```

## Wyjaśnienia

1. Do wykonania obliczeń posłużyła biblioteka bcmath - dodana w pliku Dockerfile.
2. Routing do pobrania kursów jest ustawiony w pliku `routes.yaml` i nie zawiera on nazwy akcji
   - posłużono się metodą `__invoke` - narzuca wykorzystanie jednej metody per controller
3. Obsługiwane języki są ustawiane w pliku `.env` aby można było dokonać rekonfiguracji bez dodatkowych zmian 
   w kodzie - przydatne jeśli np. operujemy na kubernetesie - wtedy zmieniamy configmapę i restartujemy aplikację.
4. W klasach domenowych wprowadzono gettery - jeśli wersja php byłaby wyższa, posłużyłbym się dostępem `public readonly`
5. Podczas pracy z NBP zauważyłem, że NBP nie udostępnia kursów w soboty i niedziele. Zrobiłem obsługę tego poprzez stosowny komunikat.
    Gdyby został użyty jakiś datepicker, można by było wyłączyć daty, które odpowiadają sobotom i niedzielom aby użytkownik nie mógł ich po prostu kliknąć.
6. Dane zawarte w tabeli można by było przenieść do oddzielnego komponentu gdyby zaszła potrzeba powtórzenia wyświetlania tabeli gdzieś indziej.
7. O ile dobrze zrozumiałem zadanie, moim celem było "zamknięcie" warstwy domeny od całej reszty. W ten sposób w domenie trzymam
    obliczenia stawki kupna i sprzedaży danej waluty. Według mnie domena powinna być otestowana w 100%. Nie mówię tu o 100% pokryciu kodu, bo to łatwo zrobić.
    Bardziej chodzi o pokrycie wszystkich przypadków jakie w domenie mogą zajść. Dla przykładu wrzuciłem parę testów.
8. Do plików `composer.json` i `package.json` nie dorzucałem nowych paczek a jedynie informację, że jest załączone dane rozszerzenie żeby phpstorm nie podświetlał niektórych kawałków kodu :)