# MailEngine CRM extension for WP Fusion

> [Magyar leírás](README_hu.md)

[MailEngine](https://www.mailengine.hu/en/) is a easy-to-use, professional emailmarketing service.
This is a custom CRM extension for [WP Fusion](https://wpfusionplugin.com/) to connect your WordPress site with your [MailEngine](https://www.mailengine.hu/en/) and syncronize contact data via WP Fusion.

![MailEngine](https://www.mailengine.hu/images/me_logo_b.svg "MailEngine emailmarketing service")

## Features

* Synchronize data to MailEngine via secure SOAP connection.
* Semi-bidirectional data sync (Pulling is available, but no webhook support). Basically bidirectional sync working triggered by WordPress event (through WP Fusion plugin) but not working directly triggered by MailEngine event.
   
  (Any data change directly in MailEngine not (yet) synchronizing into WordPress automatically.)
* Leverage data of your WordPress users and send personalized emails to them via MailEngine
* Check functions and features of [MailEngine](https://www.mailengine.hu/en/#functions)

## Getting Started

### Prerequisites

- Requires [WP Fusion](https://wpfusionplugin.com/) or [WP fusion lite](https://wordpress.org/plugins/wp-fusion-lite/)
- Requires [PHP SoapClient](https://www.php.net/manual/en/class.soapclient.php)

### Installing

This plugin is an extension for WP Fusion. 
- Install WP Fusion or WP Fusion Lite to your WordPress site.
- Then upload **MailEngine CRM extension** for WP Fusion (this plugin) via plugin upload (zipped) or copy it directly to */wp-content/plugins/* directory.

### Setup

#### Acquire necessary keys from MailEngine

If you already have a MailEngine account, contact [support](https://www.mailengine.hu/en/#contact) to acquire the required api keys to utlilize MailEngine's SOAP connection:
The required data are:

1. **client_id**
The *client_id* identifies you as a MailEngine account. This is your secret api key. Don't share with anyone who is not authorized to access to your MailEngine groups.
2. **subscribe_id**
The *subscribe_id* identifies your contact group in MailEngine. You may have access to more than one contact groups in MailEngine. 
3. **wsdl url**
https://www.mailengine.hu/extranet/wsdl/api-basic-13.wsdl
4. **affiliate** (trusted affiliate ID)
Affiliate ID sort'of identifies of the data source mainly from financial aspect. The 'person' who had brings the data. This ID is technically a MailEngine user who's the 'technical owner' of the corresponding data (not for authorization goals but for statistics). 
The default behaviour of MailEngine data submission is adding and extending. By default only submitting to previously empty fields and adding new items to multiselect fields is allowed. In order to override existing data in a MailEngine group for a contact the affiliate must be be a **trusted affiliate**!

#### Setup the CRM

1. On the WP Fusion setup page pick **MailEngine** from the CRM list.
2. Type in **wsdl url**, **subscribe_id**, **client_id**.
3. Try the connection.

#### Guide to synchronizing fields

Synchronizing the **user_email** field is compulsory. 

**MailEngine doesn't have dedicated _checkbox_ field type.** (There is a discrete _checkbox_ field type in WP Fusion to use with _boolean_ type of variables. _Boolean_ variables are special enum variables with just two 'true'/'false' or 'yes'/'no' values represented by '0' or '1'.) Checkbox type of usage can be realized by *enum* (select) type of fields in MailEngine.
Due to this, MailEngine is incompatible with _checkbox_ type in WordPress. In case of requirement please use a two-element _select_ fields instead or you may try using a number type in MailEngine.

MailEngine internally stores _select_ fields values by *key-label* pairs. However this plugin syncronizes multi/select fields by label for better compatibility with other WP plugins. 

> The *Advanced Custom Fields* plugin allows to set up select fields with key-label pairs.
> The *Ultimate Member* plugin on the other hand does not.

*WP Fusion lite only allows to synchronize basic WordPress fields. Leveraging fields created by other plugins like Advanced Custom Fields or Ultimate Member requires WP Fusion.*

Make sure you set the data type of every synchronized fields correctly. 


#### MailEngine specific options in WP Fusion

1. **Affiliate**
Adding the id of a **trusted affiliate** is required to override existing field data in the MailEngine group. Defaults to '0'. 
2. **Hidden subscribe**
If *Hidden subscribe* option is set to true, MailEngine performs an opt-in subscription to the group. This means that the newly subscribed user won't receive a confirmation email. Defaults to 'true'.
3. **Activate Unsubscribed users**
If *Activate Unsubscribed users* is set to true, then newly registered users will be readded to your MailEngine group even if they were previously unsubscribed members of the same group. Defaults to 'true'.

Otherwise follow the instructions that can be found in [setup guides and tutorials](https://wpfusion.com/documentation/) of standard WP Fusion documentation.

## MailEngine documentation

- [Fejlesztői útmutató - tagok - magyar](https://docs.google.com/document/d/1lKJSEMT-731bWRIQsVnHL8sosQkqrx6rOI_VR6bWB5k/edit#heading=h.tnjtjhbffgks)
- [Fejlesztői útmutató - kiküldések - magyar](https://docs.google.com/document/d/17ErCFzyhDO0uQ0581SnZsiCxNh7ZdtckB3snZHw2lwA/edit#heading=h.mxo62uqdt2f3)
- [developers guide - contacts - English](https://docs.google.com/document/d/1vPCd8_DrPGC1GYHEy6zyNFKy7ymYVjmj5wzUqYd30ds/edit#heading=h.xhfywkl8jbby)
- [developers guide - messages - English](https://docs.google.com/document/d/1-bE9nNbik0ckN354bix6wH2zDZ9boFUGZV33ZWgWr8E/edit)
- [MailEngine document store](https://www.mailengine.hu/en/document-library/)

## Authors

* **Jack Arturo** - *Initial work* - [Very Good Plugins](https://github.com/verygoodplugins)
* **pety-dc** - *MailEngine adaptation* - [d-code Ltd](https://github.com/d-code-ltd)

## License

This project is licensed under the GPL License - see the [LICENSE.md](LICENSE.md) file for details.
