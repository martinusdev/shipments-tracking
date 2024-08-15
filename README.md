# Shipments Tracking

![PHPUnit](https://github.com/martinusdev/shipments-tracking/actions/workflows/phpunit.yml/badge.svg)
![PHPStan](https://github.com/martinusdev/shipments-tracking/actions/workflows/phpstan.yml/badge.svg)


Tracking slovenských a českých prepravcov.

* Slovenská Pošta
* GLS SK & CZ
* SPS - Slovak Parcel Service a ParcelShop-y
* Packeta _(requires apiPassword)_


### Recommendations
- GuzzleHttp is recommended, otherwise you have to use own client
- Packeta: you need to set `PACKETA_API_PASSWORD` env to use Packeta (eg. `putenv("PACKETA_API_PASSWORD=yourApiPassword");`)
- PPL: you need to set `PPL_API_CLIENT_ID` and `PPL_API_CLIENT_SECRET` env to use PPL:
```
putenv("PPL_API_CLIENT_ID=yourApiClientId");
putenv("PPL_API_CLIENT_SECRET=yourApiClientSecret");
```
