# WP Fusion - Mailengine CRM

[Mailengine](https://www.mailengine.hu/en/) is a professional emailmarketing service.
This is a custom CRM extension for [WP Fusion](https://wpfusionplugin.com/) to connect your Wordpress site with your [Mailengine](https://www.mailengine.hu/en/).

![Mailengine](https://www.mailengine.hu/images/me_logo_b.svg "Mailengine emailmarketing service")

## Features

* Synchronize data via Soap connection
* Semi-bidirectional data sync (Pulling is available, but no webhook support)

## Getting Started

### Prerequisites

Requires [WP Fusion](https://wpfusionplugin.com/)
Requires [PHP SoapClient](https://www.php.net/manual/en/class.soapclient.php)

### Installing

This plugin is an extension for WP Fusion. 
- Install WP Fusion or WP Fusion Lite to your Wordpress site 
- Then upload **Mailengine extension** for WP Fusion (this plugin) via plugin upload (zipped) or copy it directly to */wp-content/plugins/* directory.

### Setup

#### Acquire necessary keys from Mailengine

If you already have a Mailengine account, contact [support](https://www.mailengine.hu/en/#contact) to acquire the required api keys to utlilize Mailengine's soap connection:
The required data are:

1. **client_id**
The *client_id* identifies you as a mailengine account. This is your secret api key. Don't share with anyone who is not authorized to access to your Mailengine groups
2. **subscribe_id**
The *subscribe_id* identifies your contact group in Mailengine. You may have access to more than one contact groups in Mailengine. 
3. **wsdl url**
https://www.mailengine.hu/extranet/wsdl/api-basic-13.wsdl
4. **affiliate** (trusted affiliate id)
affiliate id sort'of identifies the Mailengine user who's sending the data to Mailengine. 
The default behaviour of Mailengine data submission is adding and extending. By default only submitting to previously empty fields and adding new items to multiselect fields is allowed. In order to override existing data in a Mailengine group for a contact the affiliate must be be a **trusted affiliate** !


#### Setup the CRM

1. On the WP Fusion setup page pick **Mailengine** from the CRM list
2. Type in **wsdl url**, **subscribe_id**, **client_id**
3. Try the connection


#### Guide to synchronizing fields

Synchronizing the **user_email** field is compulsory. 

Mailengine doesn't have checkbox field type. Checkbox type of usage can be realized by *enum* (select) type of fields in Mailengine.
Due to this, Mailengine is incompatible with checkbox type in Wordpress. Use a two-element select fields instead.

Mailengine internally stores select fields values by *key-label* pairs. However this plugin syncronizes multi/select fields by label for better compatibility with other WP plugins. 

> The *Advanced Custom Fields* plugin allows to set up select fields with key-label pairs.
> The *Ultimate Member* plugin on the other hand does not.

*WP Fusion lite only allows to synchronize basic Wordpress fields. Leveraging fields created by other plugins like Advanced Custom Fields or Ultimate Member requires WP Fusion.*

Make sure you set the data type of every synchronized fields correctly. 
restrictions to type (checkbox)


#### Mailengine specific options in WP Fusion

1. **Affiliate**
Adding the id of a **trusted affiliate** is required to override existing field data in the Mailengine group. Defaults to "0". 
2. **Hidden subscribe**
If *Hidden subscribe* option is set to true, Mailengine performs an opt-in subscription to the group. This means that the newly subscribed user won't receive a confirmation email. Defaults to "true".
3. **Activate Unsubscribed users**
If *Activate Unsubscribed users* is set to true, then newly registered users will be readded to your Mailengine group even if they were previously unsubscribed members of the same group. Defaults to "true".
Otherwise follow the instructions that can be found in [setup guides and tutorials](https://wpfusion.com/documentation/) of standard WP Fusion documentation.


## Mailengine documentation

- [Fejlesztői útmutató - tagok - magyar](https://docs.google.com/document/d/1lKJSEMT-731bWRIQsVnHL8sosQkqrx6rOI_VR6bWB5k/edit#heading=h.tnjtjhbffgks)
- [Fejlesztői útmutató - kiküldések - magyar](https://docs.google.com/document/d/17ErCFzyhDO0uQ0581SnZsiCxNh7ZdtckB3snZHw2lwA/edit#heading=h.mxo62uqdt2f3)
- [developers guide - contacts - English](https://docs.google.com/document/d/1vPCd8_DrPGC1GYHEy6zyNFKy7ymYVjmj5wzUqYd30ds/edit#heading=h.xhfywkl8jbby)
- [developers guide - messages - English](https://docs.google.com/document/d/1-bE9nNbik0ckN354bix6wH2zDZ9boFUGZV33ZWgWr8E/edit)

## Authors

* **Jack Arturo** - *Initial work* - [Very Good Plugins](https://github.com/verygoodplugins)
* **@pety-dc** - *Mailengine adaptation* - [d-code Ltd](https://github.com/d-code-ltd)

## License

This project is licensed under the GPL License - see the [LICENSE.md](LICENSE.md) file for details