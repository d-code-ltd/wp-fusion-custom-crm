# Mailengine CRM kiterjesztés a WP Fusion 

> [English reacme](README.md)

A [Mailengine](https://www.mailengine.hu/hu/) egy könnyen használható, sokoldalú e-mail küldő szoftver.
Ez a repo egy egyedi CRM kiterjesztést tartalmaz a [WP Fusion](https://wpfusionplugin.com/) nevű Wordpress pluginhoz, ami lehetővé teszi, hogy összekösd a Wordpress oldalad a [Mailengine](https://www.mailengine.hu/en/) emailküldővel és szinkronizáld a feliratkozókat a rendszerek között.

![Mailengine](https://www.mailengine.hu/images/me_logo_b.svg "Mailengine emailmarketing szolgáltatás")


## Jellemzők

* Felhasználói adatok szinkronizálása a MailEngine felé biztonságos soap kapcsolaton keresztül.
* Kvázi-kétirányú adatszinkronizáció lehetősége (Adatok lekérése a Mailengine-ből lehetséges, de a webhookok nem támogatottak)
* Használd előnyödre a felhasználóid adatait és küldj számukra személyre szabott leveleket a Mailengine rendszerben.
* Nézd át a [Mailengine](https://www.mailengine.hu/#funkciok) képességeit és jellemzőit.

## Kezdjünk hozzá...

### Követelmények

- [WP Fusion](https://wpfusionplugin.com/) vagy [WP fusion lite](https://wordpress.org/plugins/wp-fusion-lite/) telepítése a Wordpressben
- telepített [PHP SoapClient](https://www.php.net/manual/en/class.soapclient.php) php kiterjesztés a kiszolgáló környezetben

### Installáció

Ez a plugin egy kiterjesztés a WP Fusionhöz.
- Telepítsd a **WP Fusion**-t vagy **WP Fusion Lite** pluginek valamelyikét a Wordpress oldaladban
- Ezt követően töltsd föl a **Mailengine CRM kiterjeszést** (ezt a plugint) a plugin feltöltése felületen, vagy másold be közvetlenül a */wp-content/plugins/* mappába

### Beállítás

#### Szükséges kulcsok megszerzése a Mailengine-től

Ha már van Mailengine fiókod, lép kapcsolatba a [supporttal](https://www.mailengine.hu/en/#contact), hogy hozzáférj a Mailengine soap kapcsolatának használatához szükséges kulcsokhoz.
Az alábbi adatokra lesz szükséged:

1. **client_id**
A *client_id* azonosítja téged, mint egy mailengine fiók. Ez egy titkos api kulcs, ne oszd meg senkivel, aki nem jogosult hozzáférni a Mailengine adatbázisodhoz
2. **subscribe_id**
A *subscribe_id* azonosítja az adatbázisodat a Mailengineben. Akár több adatbázisod is lehet a Meilangine-ben.
3. **wsdl url**
https://www.mailengine.hu/extranet/wsdl/api-basic-13.wsdl
4. **affiliate** (trusted affiliate id)
Az affliate azonosító azt azonosítja, hogy úgymond "ki küldi be" az aatokat a Mailengine-be.
A Mailengine alapértelmezett működése olyan, hogy a beküldött felhasználói adatokkal a már tárolt adatokat csak kiegészíteni lehet: korábban ki nem töltött mezők értékét lehet hozzáadni, vagy többértékes mezők esetén elemeket hozáadni. Annak érdekében, hogy meglevő adatokat felül lehessen írni szükséges egy úgynevezett megbízható minősítéssel rendelkező adatfrissítő, vagyis **trusted affiliate** azonosító.

#### A CRM Beállítása

1. A WP Fusion beállítási fülén válaszd ki a CRM listából **Mailengine-t** !
2. Add meg a  **wsdl url**, **subscribe_id**, **client_id** adatokat!
3. Teszteld a kapcsolatot!


#### Útmutató a mezők szinkronizációjához

A **user_email** mező szinkronizálása kötelező.

A Mailengine-ben sajnos nincs checkbox típusú mező. Checkbox típusú felhasználása az *enum* mezőtípus használható a Mailengineben.
Emiatt a Mailengine nem kompatibilis a Wordpressben létrehozott checkbox típusú mezővel. EHelyett a Wordpressben kételemű select típusú mezők használata javallott, vagy esetleg meg lehet próbálni a Mailengine-ben szám típusú mezőben tárolni a checkbox értékeket.

A Mailengine a háttérrendszerben a *select* típusú mezők értékét *kulcs-szöveg* párokként tárolja. Azonban ez a plugin a select/multiselect típusú mezők értékét más Wordpress pluginokkal való magasabb fokú kompatibilitás érdekében szöveg formájukban szinkronizálja. 

> Az *Advanced Custom Fields* plugin például lehetővé teszi, hogy select típusú mezők esetén meg lehessen adni a kiválasztható értékeknek kulcsot és szövegfeliratot is.
> Az *Ultimate Member* plugin esetén azonban többelemű kiválaszó mező esetén csak a szövegfeliratok adhatók meg

*A WP Fusion lite csak az alap Wordpress mezők szinkronizálását teszi lehetővé. Más pluginokban, mint pl az Advanced Custom Fields vagy az Ultimate Member, létrehozott mezők szinkronizálásához a WP Fusion szükséges.*

Mindenképp állítsd a szinkronizált mezőkhöz a helyes adattípust is!

Make sure you set the data type of every synchronized fields correctly. 


#### Mailengine beállítások a WP Fusionben

1. **Affiliate**
**trusted affiliate** megadása szükséges ahhoz, hogy a felhasználók Mailengine-ben tárolt értékeit a Wordpress oldalról felül lehessen bírálni. Ez az érték alapértelmezetten "0".
2. **Hidden subscribe**
Ha a *Hidden subscribe* kapcsoló igaz, akkor a Mailengine egy olyan feliratást hajt végre az adatbázisban, ami nem igényel megerősítést. Ez azt jelenti, hogy az újonnan regisztrált felhasználók egyből bekerülnek a Mailengine adatbázisba is, nem fognak megerősítő emailt kapni. Ez a beállítás alapértelmezetten "igaz".
3. **Activate Unsubscribed users**
Ha az *Activate Unsubscribed users* kapcsoló igaz, akkor az újonnan regisztrált felhasználók újra bekerülnek a Mailengine adatbázisba, amennyiben azt megelőezően már tagok voltak ott és leiratkoztak. Ez a beállítás alapértelmezetten "igaz".

Más tekintetében hasznos átnézni és praktikus követni a WP fusion dokumentációjában található beállításokra vonatkozó [útmutatókat és javaslatokat](https://wpfusion.com/documentation/)


## Mailengine dokumentáció

- [Fejlesztői útmutató - tagok - magyar](https://docs.google.com/document/d/1lKJSEMT-731bWRIQsVnHL8sosQkqrx6rOI_VR6bWB5k/edit#heading=h.tnjtjhbffgks)
- [Fejlesztői útmutató - kiküldések - magyar](https://docs.google.com/document/d/17ErCFzyhDO0uQ0581SnZsiCxNh7ZdtckB3snZHw2lwA/edit#heading=h.mxo62uqdt2f3)
- [developers guide - contacts - English](https://docs.google.com/document/d/1vPCd8_DrPGC1GYHEy6zyNFKy7ymYVjmj5wzUqYd30ds/edit#heading=h.xhfywkl8jbby)
- [developers guide - messages - English](https://docs.google.com/document/d/1-bE9nNbik0ckN354bix6wH2zDZ9boFUGZV33ZWgWr8E/edit)
- [Mailengine dokumentumtár](https://www.mailengine.hu/hu/dokumentumtar/)

## Authors

* **Jack Arturo** - *Initial work* - [Very Good Plugins](https://github.com/verygoodplugins)
* **@pety-dc** - *Mailengine adaptation* - [d-code Ltd](https://github.com/d-code-ltd)

## License

This project is licensed under the GPL License - see the [LICENSE.md](LICENSE.md) file for details