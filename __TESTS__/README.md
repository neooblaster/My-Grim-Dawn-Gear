# Build Session Behavior

## 1. From Brand New Build

### 1.1. Brand new build - w/o ITEMS - w/o NAME - w/o PASSWORD

#### Before :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : SAVE

#### Actions :

* Click on INPUT_SUBMIT (save)

#### Excpected & Test

* Excpected : Alert saying build is empty
* Test : OK

#### After :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : SAVE


<hr />


### 1.2. Brand new build - w/o ITEMS - w/o NAME - w/o PASSWORD - ITEM SETTED & REMOVED

#### Before :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : SAVE

#### Actions :

* Click en Search button
* Drag and drop item on corresponding slot
* Remove it
* Click on INPUT_SUBMIT (save)

#### Excpected & Test

* Excpected : Alert saying build is empty
* Test : OK

#### After :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : SAVE


<hr />


### 1.3. Brand new build - w/ ITEMS - w/o NAME - w/o PASSWORD

#### Before :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : SAVE

#### Actions :

* Click en Search button
* Drag and drop item on corresponding slot
* Click on INPUT_SUBMIT (save)

#### Excpected & Test

* Excpected : Alert with the new build URL. Then reload page.
* Test : OK

#### After :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : SAVE
* INPUT_LINKERS : URL + IMG


<hr />


### 1.4. Brand new build - w/ ITEMS - w/ NAME - w/o PASSWORD

#### Before :

* INPUT_NAME : TEST_1.4.
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : SAVE

#### Actions :

* Click en Search button
* Drag and drop item on corresponding slot
* Type a name in INPUT_NAME
* Click on INPUT_SUBMIT (save)

#### Excpected & Test

* Excpected : Alert with the new build URL. Then reload page which has build name instead GEAR BUILDER.
* Test : OK

#### After :

* INPUT_NAME : TEST_1.4
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : SAVE
* INPUT_LINKERS : URL + IMG


<hr />


### 1.5. Brand new build - w/ ITEMS - w/ NAME - w/ PASSWORD

#### Before :

* INPUT_NAME : TEST_1.5.
* INPUT_PASSWORD : Password
* INPUT_SUBMIT : SAVE

#### Actions :

* Click en Search button
* Drag and drop item on corresponding slot
* Type a name in INPUT_NAME
* Type a password in INPUT_PASSWORD
* Click on INPUT_SUBMIT (save)

#### Excpected & Test

* Excpected : Alert with the new build URL. Then reload page which has build name instead GEAR BUILDER.
* Test : OK

#### After :

* INPUT_NAME : TEST_1.4
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : SAVE
* INPUT_BUTTON : SIGN TO EDIT
* INPUT_LINKERS : URL + IMG


<hr />


### 1.6. Brand new build - w/ ITEMS - w/o NAME - w/ PASSWORD

#### Before :

* INPUT_NAME : EMPTY.
* INPUT_PASSWORD : Password
* INPUT_SUBMIT : SAVE

#### Actions :

* Click en Search button
* Drag and drop item on corresponding slot
* Type a password in INPUT_PASSWORD
* Click on INPUT_SUBMIT (save)

#### Excpected & Test

* Excpected : Alert with the new build URL. Then reload page.
* Test : OK

#### After :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : SAVE
* INPUT_BUTTON : SIGN TO EDIT
* INPUT_LINKERS : URL + IMG


<hr />


### 1.7. From existing build - Save w/o changes

#### Before :

* INPUT_NAME : EMPTY.
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : SAVE

#### Actions :

* Click on INPUT_SUBMIT (save)

#### Excpected & Test

* Excpected : Alert saying nothing change.
* Test : OK

#### After :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : SAVE
* INPUT_BUTTON : SIGN TO EDIT
* INPUT_LINKERS : URL + IMG


<hr />


### 1.8. Clear existing build

#### Before :

* INPUT_NAME : EMPTY

#### Actions :

* Remove all slots items
* Click on INPUT_SUBMIT (save)

#### Excpected & Test

* Excpected : Alert saying build is empty.
* Test : OK

#### After :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : SAVE
* INPUT_BUTTON : SIGN TO EDIT
* INPUT_LINKERS : URL + IMG


<hr />


### 1.9. Save an edited build w/o signed

#### Before :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : SAVE
* INPUT_BUTTON : SIGN TO EDIT
* INPUT_LINKERS : URL + IMG

#### Actions :

* Add or change items
* Click on INPUT_SUBMIT (save)

#### Excpected & Test

* Excpected :  Alert with the new build URL. Then reload page.
* Test : OK

#### After :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : SAVE
* INPUT_LINKERS : URL + IMG


<hr />


### 1.10. Save an edited build w/o sign w/ password

#### Before :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : Password
* INPUT_SUBMIT : SAVE
* INPUT_BUTTON : SIGN TO EDIT
* INPUT_LINKERS : URL + IMG

#### Actions :

* Add or change items
* Type password in INPUT_PASSWORD
* Click on INPUT_SUBMIT (save)

#### Excpected & Test

* Excpected :  Alert with the new build URL. Then reload page.
* Test : OK

#### After :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : SAVE
* INPUT_BUTTON : SIGN TO EDIT
* INPUT_LINKERS : URL + IMG


<hr />


### 1.11. Sign on protected build

#### Before :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : SAVE
* INPUT_BUTTON : SIGN TO EDIT
* INPUT_LINKERS : URL + IMG

#### Actions :

* Click on INPUT_BUTTON (Sign to Edit)

#### Excpected & Test

* Excpected :  INPUT_SUBMIT renamed as UPDATE and INPUT_BUTTON renamed as SAVE AS COPY.
* Test : OK

#### After :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : UPDATE
* INPUT_BUTTON : SAVE AS COPY
* INPUT_LINKERS : URL + IMG


<hr />


### 1.12. Sign on protected build and reload page

#### Before :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : SAVE
* INPUT_BUTTON : SIGN TO EDIT
* INPUT_LINKERS : URL + IMG

#### Actions :

* Click on INPUT_BUTTON (Sign to Edit)
* Reload page (F5)

#### Excpected & Test

* Excpected :  INPUT_SUBMIT is UPDATE and INPUT_BUTTON is SAVE AS COPY.
* Test : OK

#### After :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : UPDATE
* INPUT_BUTTON : SAVE AS COPY
* INPUT_LINKERS : URL + IMG


<hr />


### 1.12. Update Protected Build

#### Before :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : UPDATE
* INPUT_BUTTON : SAVE AS COPY
* INPUT_LINKERS : URL + IMG

#### Actions :

* Make change on build
* Click on INPUT_SUBMT (Update)
* Reload page (F5)
* CHange must been applied

#### Excpected & Test

* Excpected :  Update done successfully
* Test : OK

#### After :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : UPDATE
* INPUT_BUTTON : SAVE AS COPY
* INPUT_LINKERS : URL + IMG


<hr />


### 1.12. Save as Copy from Protected Build

#### Before :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : UPDATE
* INPUT_BUTTON : SAVE AS COPY
* INPUT_LINKERS : URL + IMG

#### Actions :

* Make change on build
* Click on INPUT_BUTTON (Save as Copy)
* Alert with the new build URL. Then reload page.
* CHange must been applied

#### Excpected & Test

* Excpected :  Update done successfully
* Test : OK

#### After :

* INPUT_NAME : EMPTY
* INPUT_PASSWORD : EMPTY
* INPUT_SUBMIT : SAVE
* INPUT_BUTTON : SIGN TO EDIT
* INPUT_LINKERS : URL + IMG