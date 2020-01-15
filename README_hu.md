# MailEngine CRM kiterjesztés a WP Fusion-höz

> [English readme](README.md)

A [MailEngine](https://www.mailengine.hu/hu/) egy könnyen használható, sokoldalú e-mail küldő szoftver.
Ez a repo egy egyedi CRM kiterjesztést tartalmaz a [WP Fusion](https://wpfusionplugin.com/) nevű WordPress pluginhoz, ami lehetővé teszi, hogy összekösd a WordPress oldalad a [MailEngine](https://www.mailengine.hu/en/) emailküldővel és szinkronizáld a feliratkozókat a rendszerek között.

![MailEngine](https://www.mailengine.hu/images/me_logo_b.svg "MailEngine emailmarketing szolgáltatás")

## Jellemzők

* Felhasználói adatok szinkronizálása a MailEngine felé biztonságos SOAP kapcsolaton keresztül.
* Kvázi-kétirányú adatszinkronizáció lehetősége (Adatok lekérése a MailEngine-ből lehetséges, de a webhookok nem támogatottak). Alapvető kétirányú szinkron működik WordPress események által indukálva (a WP Fusion plugin-en keresztül) de nem működnek a direkt MailEngine eseményeből adódóan.

  (Bármilyen a MailEngine-ben történő direkt adatváltozás nem szinkronizálódik a WordPress-be automatikusan (még).)
* Használd előnyödre a felhasználóid adatait és küldj számukra személyre szabott leveleket a MailEngine rendszerben.
* Nézd át a [MailEngine](https://www.mailengine.hu/#funkciok) képességeit és jellemzőit.

## Kezdjünk hozzá...

### Követelmények

- [WP Fusion](https://wpfusionplugin.com/) vagy [WP fusion lite](https://wordpress.org/plugins/wp-fusion-lite/) telepítése a WordPressben.
- Telepített [PHP SoapClient](https://www.php.net/manual/en/class.soapclient.php) PHP kiterjesztés a kiszolgáló környezetben.

### Installáció

Ez a plugin egy kiterjesztés a WP Fusionhöz.
- Telepítsd a **WP Fusion**-t vagy **WP Fusion Lite** pluginek valamelyikét a WordPress oldaladban.
- Ezt követően töltsd föl a **MailEngine CRM kiterjeszést** (ezt a plugint) a plugin feltöltése felületen, vagy másold be közvetlenül a */wp-content/plugins/* mappába.

### Beállítás

#### Szükséges kulcsok megszerzése a MailEngine-től

Ha már van MailEngine fiókod, lép kapcsolatba a [supporttal](https://www.mailengine.hu/en/#contact), hogy hozzáférj a MailEngine SOAP kapcsolatának használatához szükséges kulcsokhoz.
Az alábbi adatokra lesz szükséged:

1. **client_id**
A *client_id* azonosítja téged, mint egy MailEngine fiók. Ez egy titkos api kulcs, ne oszd meg senkivel, aki nem jogosult hozzáférni a MailEngine adatbázisodhoz.
2. **subscribe_id**
A *subscribe_id* azonosítja az adatbázisodat a MailEngineben. Akár több adatbázisod is lehet a Meilangine-ben.
3. **wsdl url**
https://www.mailengine.hu/extranet/wsdl/api-basic-13.wsdl
4. **affiliate** (trusted affiliate ID)
Az affliate azonosító azt azonosítja az adat forrását legfőképpen pénzügyi elszámolás szempontjából. Az a 'személy', aki az adatot hozta. Ez az ID technikailag egy MailEngine felhasználó, aki a 'technikai tulajdonosa' a kapcsolódó adatnak (nem jogosultság kezelés miatt, sokkal inkább pénzügyi szempontból).
A MailEngine alapértelmezett működése olyan, hogy a beküldött felhasználói adatokkal a már tárolt adatokat csak kiegészíteni lehet: korábban ki nem töltött mezők értékét lehet hozzáadni, vagy többértékes mezők esetén elemeket hozáadni. Annak érdekében, hogy meglevő adatokat felül lehessen írni szükséges egy úgynevezett megbízható minősítéssel rendelkező adatfrissítő, vagyis **trusted affiliate** azonosító.

#### A CRM Beállítása

1. A WP Fusion beállítási fülén válaszd ki a CRM listából **MailEngine-t** !
2. Add meg a  **wsdl url**, **subscribe_id**, **client_id** adatokat!
3. Teszteld a kapcsolatot!

#### Útmutató a mezők szinkronizációjához

A **user_email** mező szinkronizálása kötelező.

**A MailEngine-ben sajnos nincs _checkbox_ típusú mező.** (A WP Fusion rendelkezik diszkrét _checkbox_ mező típussal a boolean típusú változók használatához. A _boolean_ változók speciális lista változók összesen két, 'igaz'/'hamis' vagy 'igen'/'nem' értékkel, '0' vagy '1'-ként ábrázolva.) Checkbox típusú felhasználása az *enum* mezőtípus használható a MailEngineben.
Emiatt a MailEngine nem kompatibilis a WordPressben létrehozott _checkbox_ típusú mezővel. Ehelyett a WordPressben kételemű _select_ típusú mezők használata javallott, vagy esetleg meg lehet próbálni a MailEngine-ben szám típusú mezőben tárolni a _checkbox_ értékeket.

A MailEngine a háttérrendszerben a *select* típusú mezők értékét *kulcs-szöveg* párokként tárolja. Azonban ez a plugin a select/multiselect típusú mezők értékét más WordPress pluginokkal való magasabb fokú kompatibilitás érdekében szöveg formájukban szinkronizálja. 

> Az *Advanced Custom Fields* plugin például lehetővé teszi, hogy select típusú mezők esetén meg lehessen adni a kiválasztható értékeknek kulcsot és szövegfeliratot is.
> Az *Ultimate Member* plugin esetén azonban többelemű kiválaszó mező esetén csak a szövegfeliratok adhatók meg

*A WP Fusion lite csak az alap WordPress mezők szinkronizálását teszi lehetővé. Más pluginokban, mint pl az Advanced Custom Fields vagy az Ultimate Member, létrehozott mezők szinkronizálásához a WP Fusion szükséges.*

Mindenképp állítsd a szinkronizált mezőkhöz a helyes adattípust is!

Make sure you set the data type of every synchronized fields correctly. 


#### MailEngine beállítások a WP Fusionben

1. **Affiliate**
**trusted affiliate** megadása szükséges ahhoz, hogy a felhasználók MailEngine-ben tárolt értékeit a WordPress oldalról felül lehessen bírálni. Ez az érték alapértelmezetten '0'.
2. **Hidden subscribe**
Ha a *Hidden subscribe* kapcsoló igaz, akkor a MailEngine egy olyan feliratást hajt végre az adatbázisban, ami nem igényel megerősítést. Ez azt jelenti, hogy az újonnan regisztrált felhasználók egyből bekerülnek a MailEngine adatbázisba is, nem fognak megerősítő emailt kapni. Ez a beállítás alapértelmezetten 'igaz'.
3. **Activate Unsubscribed users**
Ha az *Activate Unsubscribed users* kapcsoló igaz, akkor az újonnan regisztrált felhasználók újra bekerülnek a MailEngine adatbázisba, amennyiben azt megelőezően már tagok voltak ott és leiratkoztak. Ez a beállítás alapértelmezetten 'igaz'.

Más tekintetében hasznos átnézni és praktikus követni a WP fusion dokumentációjában található beállításokra vonatkozó [útmutatókat és javaslatokat](https://wpfusion.com/documentation/).

## MailEngine API dokumentáció

- [Felhasználók - Fejlesztői útmutató - Magyar](https://docs.google.com/document/d/1lKJSEMT-731bWRIQsVnHL8sosQkqrx6rOI_VR6bWB5k/edit#heading=h.tnjtjhbffgks)
- [Üzenetek - Fejlesztői útmutató - Magyar](https://docs.google.com/document/d/17ErCFzyhDO0uQ0581SnZsiCxNh7ZdtckB3snZHw2lwA/edit#heading=h.mxo62uqdt2f3)
- [Contacts - Developer's guide - English](https://docs.google.com/document/d/1vPCd8_DrPGC1GYHEy6zyNFKy7ymYVjmj5wzUqYd30ds/edit#heading=h.xhfywkl8jbby)
- [Messages - Developer's guide - English](https://docs.google.com/document/d/1-bE9nNbik0ckN354bix6wH2zDZ9boFUGZV33ZWgWr8E/edit)
- [MailEngine dokumentumtár](https://www.mailengine.hu/hu/dokumentumtar/)

## Authors

* **Jack Arturo** - *Initial work* - [Very Good Plugins](https://github.com/verygoodplugins)
* **pety-dc** - *MailEngine adaptation* - [d-code Ltd](https://github.com/d-code-ltd)

## License

This project is licensed under the GPL License - see the [LICENSE.md](LICENSE.md) file for details.
