# სამუშაო ჟურნალი (WORKLOG)

მუდმივი წესი ამ ფაილის შესახებ განსაზღვრულია `CLAUDE.md`-ში ("## Work log" სექცია): ყოველი მნიშვნელოვანი ცვლილების შემდეგ აქ ემატება ჩანაწერი — თარიღი, რა გაკეთდა, რომელი ფაილები შეიცვალა. უახლესი ჩანაწერი ზემოთაა.

---

## 2026-07-15 — Quill (`<x-rich-editor>`) აღარ იტვირთებოდა row-item-ების ენების ტაბებში — გასწორდა

წინა ორი ჩანაწერით დამატებული pl/es ველების ტესტირებისას აღმოჩნდა, რომ row-item-ის (სლაიდი/ბლოკი) ტექსტის რედაქტორი (Quill) საერთოდ არ იტვირთებოდა, როცა ის `<x-i18n-tabs>`-ის named slot-ში (`<x-slot:ka>` და ა.შ.) იყო ჩალაგებული — გვერდზე ჩნდებოდა დაუსტილო, ერთმანეთზე გადაფარებული უბრალო ტექსტი Quill toolbar-ის ნაცვლად ("ტექსტი ჩაწერის გრაფებია არეულია"). ბრაუზერის კონსოლში იყო "Quill is not defined".

**ძირეული მიზეზი:** `<x-rich-editor>`-ს ჰქონდა `@once` ბლოკი Quill-ის CSS/JS-ის ერთხელ ჩასატვირთად. Blade-ს აქვს ცნობილი შეზღუდვა — `@once` არასწორად მუშაობს (საერთოდ არ იბეჭდება, თუნდაც პირველად), როცა კომპონენტი სხვა კომპონენტის named slot-შია ჩალაგებული (ჩვენს შემთხვევაში სწორედ `<x-i18n-tabs>`-ის slot-ები). დადასტურდა იზოლირებული ტესტით: 2 `<x-rich-editor>` უბრალო `<div>`-ში — მუშაობს; იგივე ორი `<x-slot:ka>`/`<x-slot:en>`-ში ჩალაგებული — Quill-ის სკრიპტი საერთოდ არ იბეჭდება.

**გასწორება:** Quill-ის CSS/JS აღარ არის `@once`-ით `rich-editor.blade.php`-ში, არამედ ყოველთვის იტვირთება ერთხელ `layouts/admin.blade.php`-ის `<head>`-ში (მცირე, უვნებელი ხარჯია ყველა ადმინის გვერდზე ჩატვირთვა, სამაგიეროდ Blade-ის ამ შეზღუდვას სრულად გვერდს უვლის).

**შეცვლილი:** `resources/views/layouts/admin.blade.php`, `resources/views/components/rich-editor.blade.php`.

---

## 2026-07-15 — ადმინის ტექსტ-ველები მხოლოდ Settings-ში ჩართულ ენებს აჩვენებს (5 ენა: ka/en/de/pl/es)

`Setting::LOCALES`-ში სინამდვილეში 5 ენაა განსაზღვრული (ka/en/de/pl/es) და routes/web.php-იც სრულად უჭერს მხარს ყველას (`{locale}` prefix `ka|en|de|pl|es`-ზეა შეზღუდული), მაგრამ ადმინის ყველა შემცველობის ფორმა (გვერდი/row/row-item/პროდუქტი/სიახლე/მენიუს პუნქტი) მხოლოდ 3 ენას (ka/en/de) აჩვენებდა — უპირობოდ, მიუხედავად იმისა, Settings-ში რომელი ენებია რეალურად ჩართული. მომხმარებელს Settings-ში ჰქონდა არჩეული English + Polski (`default_locale=en`, `locales=["pl"]`), მაგრამ ტექსტის შესაყვან ველებში მაინც ქართული/ინგლისური/გერმანული ჩანდა.

გასწორდა: გაზიარებული `x-i18n-tabs` კომპონენტი (`resources/views/components/i18n-tabs.blade.php`) ახლა დინამიურად გადის `Setting::LOCALES`-ზე და მხოლოდ `Setting::current()->isLocaleEnabled($code)`-ით ჩართულ ენებს რენდერავს (როგორც tab-ღილაკს, ისე თვითონ ველს) — გამორთული ენის input საერთოდ აღარ ჩნდება DOM-ში, არა მხოლოდ ვიზუალურად იმალება. ყველა ადმინის ფორმას (pages/edit, pages/create, rows/edit — სათაური/ქვესათაური/ტექსტი და row-item-ების ველებიც, products/_form, articles/_form, menu/index) დაემატა `pl`/`es` x-slot-ები, რომ კომპონენტს რეალურად ჰქონდეს რისი ჩვენებაც. `RowController` და `RowItemController`-ის ვალიდაცია, რომელიც აქამდე ხელით hardcode-ილი ka/en/de წესებით მუშაობდა, გადავიდა უკვე არსებულ `translatableRules()` helper-ზე (იგივეზე, რასაც Page/Product/Article/MenuItem უკვე იყენებდნენ) — ეს ავტომატურად ფარავს ყველა 5 ენას.

**შეცვლილი:** `resources/views/components/i18n-tabs.blade.php`, `resources/views/admin/{pages/edit,pages/create,rows/edit,products/_form,articles/_form,menu/index}.blade.php`, `app/Http/Controllers/Admin/{RowController,RowItemController}.php`.

---

## 2026-07-15 — text row-ის მრავალსვეტიანი (columns>1) განშტოება საერთოდ არ პატივს სცემდა group_text_color/bare-ს

წინა ორი ჩანაწერის ფიქსი მხოლოდ `text.blade.php`-ის ერთსვეტიან (`columns<=1`) განშტოებას ეხებოდა. აღმოჩნდა, რომ იმავე ფაილის **მეორე**, მრავალსვეტიანი განშტოება (`columns>1`, items-იანი ბლოკები — სურათი+სათაური+ტექსტი გვერდიგვერდ) `style="{{ $row->styleAttr() }}"`-ს **უპირობოდ** იყენებდა, საერთოდ არ ამოწმებდა `$bare`/`$groupTextColor`-ს. სწორედ ეს იყო რეალურად პრობლემური row (`id=43`, `page_id=1`, `columns=3`, ჯგუფის თავი) — მასზე „ჯგუფის ტექსტის ფერის" შეცვლას არანაირი ეფექტი არ ჰქონდა, რადგან ეს კონკრეტული branch საერთოდ არ ერეოდა ამ მექანიზმში (და ამასთანავე საკუთარ padding-საც ორმაგად აყენებდა ჯგუფის padding-ზე ზემოთ).

გასწორდა: მეორე განშტოებაც იმავე `$bare ? ($groupTextColor ?: $row->textColorStyle()) : $row->styleAttr()` ლოგიკას იყენებს, რაც პირველშია. დადასტურდა ტესტით row 43-ზე.

**შეცვლილი:** `resources/views/site/rows/text.blade.php`.

---

## 2026-07-15 — დაემატა „ჯგუფის ტექსტის ფერი" — ერთიანი ტექსტის ფერი group_shared_background ჯგუფისთვის

წინა ჩანაწერის გასწორებით ჯგუფურ (group_shared_background) row-ებზე თითოეული წევრის საკუთარი `text_color` დაუბრუნდა ეფექტს, მაგრამ ხშირად სასურველია მთელი ჯგუფისთვის ერთიანი ტექსტის ფერის არჩევა (განსაკუთრებით რადგან ფონიც საერთოა) — თითოეული სვეტის ცალ-ცალკე რედაქტირების ნაცვლად. დაემატა ახალი `group_text_color` პარამეტრი, ანალოგიური არსებული „ჯგუფის ფონის ფერისა": თუ დაყენებულია, გადაფარავს ჯგუფის ყველა წევრის საკუთარ `text_color`-ს; თუ არა, ყველაფერი ისევ თითოეული row-ის საკუთარი ფერით მუშაობს (ცვლილებამდელი ქცევა). Admin-ის ფორმაში ველის საწყისი მნიშვნელობა ნაგულისხმევად უკვე შენახულ `text_color`-ზეა დაყენებული, ანუ შენახვამდე არაფერი იცვლება ვიზუალურად.

**შეცვლილი:** `app/Models/Row.php` (`groupTextColor()`, `groupBackgroundStyle()`-ში `color` დამატებულია), `app/Http/Controllers/Admin/RowController.php` (ვალიდაცია + შენახვა), `resources/views/admin/rows/edit.blade.php` (ახალი ველი), `resources/views/site/page.blade.php` (`$groupTextColor` გადაეცემა წევრებს), `resources/views/site/rows/{text,products,news,image,map,form}.blade.php`.

---

## 2026-07-15 — Row-ის „ტექსტის ფერი" აღარ იშლება ჯგუფურ (group_shared_background) row-ებზე

ბაგი: როცა Row გვერდიგვერდ ჯგუფშია (`group_size > 1`) და ჩართულია „საერთო ფონი ჯგუფისთვის" (`group_shared_background`), ყველა row ტიპის template (text/products/news/image/map/form) ბარე რენდერისას მთლიანად აგდებდა `$row->styleAttr()`-ს (`$bare ? '' : $row->styleAttr()`), რაც ერთდროულად შლიდა ფონსაც *და* row-ის საკუთარ `text_color`-საც — ჯგუფის wrapper-ის `Row::groupBackgroundStyle()` კი მხოლოდ ფონსა და padding-ს აწესებდა, `color`-ს არასდროს. შედეგად ჯგუფურ row-ზე admin-ის „ტექსტის ფერი" პარამეტრს არავითარი ეფექტი არ ჰქონდა (რეალურ მაგალითად აღმოჩნდა `id=43`, `page_id=1`, `text_color=#bababa`).

გასწორდა: `Row`-ს დაემატა `textColorStyle()` (აბრუნებს მხოლოდ `color:...`-ს, თუ `text_color` დაყენებულია), და ექვსივე template-ში ბარე შემთხვევაში ფონის ნაცვლად ეს გამოიყენება (`$bare ? $row->textColorStyle() : $row->styleAttr()`) — ფონი/padding კვლავ საერთოა ჯგუფისთვის, მაგრამ თითოეული წევრის საკუთარი ტექსტის ფერი ისევ მოქმედებს.

**შეცვლილი:** `app/Models/Row.php`, `resources/views/site/rows/{text,products,news,image,map,form}.blade.php`.

---

## 2026-07-15 — flip-ბარათის უკანა მხარეს რიჩ-ტექსტის ფორმატირება დაუბრუნდა

პროდუქტების Row-ის flip card style-ში (`Row::cardStyle() === 'flip'`) hover-ზე ბრუნვისას გამოჩენილი უკანა მხარის ტექსტი აქამდე `productSummary()`-ით მიდიოდა, რომელიც აბზაცებს, ფერებს და ზომებს (Quill-ის inline CSS-ს) მთლიანად შლიდა ერთ plain-text ბლოკად. ახლა უკანა მხარეს იგივე ლოგიკით რენდერდება, რაც სია (`list`) და კარუსელის (`carousel`) სტილებშია — excerpt ან body (თუ HTML შეიცავს) `prose`-ში, ორიგინალი აბზაცებით და inline CSS-ით შენარჩუნებული. ბარათს ფიქსირებული სიმაღლე აქვს, ამიტომ გრძელი ტექსტისთვის `.product-flip-card-back-body`-ს `overflow-y: auto` დაემატა, header (სათაური) და ფასი კი `flex-shrink: 0`-ით ყოველთვის ჩანან.

**შეცვლილი:** `resources/views/site/rows/products.blade.php` (flip card back-ის markup), `resources/css/app.css` (`.product-flip-card-back-body` overflow წესი).

---

## 2026-07-15 — პროექტის დაარსება: polnuts, brnuts-ის ასლის საფუძველზე

ეს პროექტი (`polnuts`) შეიქმნა როგორც `brnuts`-ის სრული ლოკალური ასლი, ახალი სახელით. Git ისტორია სრულად შემონახულია (`git clone` ლოკალურად brnuts-იდან), მაგრამ `origin` remote მოშორებულია — brnuts-ის GitHub repo-სთან კავშირი აღარ არსებობს, ახალი remote საჭიროების შემთხვევაში ცალკე დაემატება.

რა გაკეთდა:
- ლოკალური MySQL ბაზა `polnuts` შეიქმნა და brnuts-ის ბაზის სრული dump (`mysqldump`) შიგნით ჩაიტვირთა — ანუ ყველა პროდუქტი, გვერდი, row და მენიუ თავიდან იმეორებს brnuts-ს.
- `storage/app/public`-ის media ფაილები (29 ფაილი) ხელით დაკოპირდა brnuts-იდან.
- ახალი `.env` შეიქმნა: `APP_NAME=Polnuts`, `APP_URL=http://polnuts.test`, `DB_DATABASE=polnuts`, ახალი `APP_KEY`.
- `Setting.site_name` განახლდა `BRNUTS`-იდან `POLNUTS`-ზე ბაზაში პირდაპირ (tinker-ით).
- Laragon-ის auto-vhost-მა ავტომატურად დაამატა `polnuts.test` hosts ფაილში.
- დადასტურდა: homepage HTTP 200, `<title>HOME · POLNUTS</title>`, CSS/JS ასეტები იტვირთება (`public/build/*` უკვე git-ში იყო commit-ილი brnuts-იდან, ხელახლა build არ დასჭირვებია).

**ჯერ არ არის განახლებული** (მომდევნო ნაბიჯებია, თუ polnuts დამოუკიდებელ ბრენდად ვითარდება): `Setting.logo`/`Setting.favicon` სურათები (ჯერ კიდევ BRNUTS ლოგოა ატვირთული), `Setting.email` (ჯერ კიდევ `info@brnuts.de`), `Setting.phone`, და ცალკე GitHub repo/production deploy — ეს ყველაფერი brnuts-ის კონტენტიდან პირდაპირ არის დაკოპირებული და საჭიროებს ცალკე გადაწყვეტილებას.

**შეცვლილი/შექმნილი:** მთელი `C:\laragon\www\polnuts` საქაღალდე (ახალი git clone), ახალი MySQL ბაზა `polnuts`, `.env`, `storage/app/public/*`.

---

## 2026-07-15 — პირველი production deploy (brnuts.de)

პროექტი პირველად აიტვირთა production-ზე, ახალ დომენზე `brnuts.de` (cPanel ანგარიში `brnutsde`, ჰოსტინგი proservice.ge — იგივე პროვაიდერი, სადაც ძველი `royalnuts.com.ge` წევს, მაგრამ სულ ახალი/ცალკე cPanel ანგარიშით, არა იმავე დირექტორიაში). დეტალური სერვერის სტრუქტურა და გამეორებადი პროცედურა შენახულია პროექტის მეხსიერებაში (`deploy-brnuts-server` memory).

მოკლედ რა გაკეთდა:
- 3 commit (flip ბარათი, sticky header, WORKLOG პროცესი) push-ილია `origin/main`-ზე (`github.com/gere1/brnuts`, ყოფილი `gere1/royalnuts`, უბრალოდ გადარქმეული).
- სერვერზე (`~/app`) კოდი დაკლონირდა GitHub-იდან ახალი deploy key-ით (read-only), `~/public_html` დაუკავშირდა `~/app/public`-ს (symlink `build`/`storage`-ზე, ხელით მორგებული `index.php`, შერწყმული `.htaccess` — cPanel-ის PHP handler + Laravel-ის rewrite წესები ერთად).
- ლოკალური `brnuts` ბაზის dump საწყისად ხელით აიტვირთა phpMyAdmin-ით (`mysqldump`-ით გენერირებული, `Desktop\brnuts.sql`), ასევე `storage/app/public`-ის media ფაილები (zip → cPanel File Manager → extract).
- გამოსწორდა 2 პრობლემა, რომლებმაც საიტი 500-ზე აყენებდა: ცარიელი `APP_KEY` (`.env`-ში default-ად დარჩენილი, `key:generate` + `config:cache` ხელახლა) და დაკომენტარებული `DB_*` ხაზები `.env`-ში (`sed`-ით მოშორდა `#`).
- საკონტაქტო ფორმის მეილი არ მოდიოდა, რადგან `.env.example`-ის default `MAIL_MAILER=log` იყო დარჩენილი (მეილი მხოლოდ log ფაილში იწერებოდა, არ იგზავნებოდა) — გამოსწორდა `MAIL_MAILER=sendmail`-ზე გადართვით, დადასტურდა რეალური ტესტ-შეტყობინებით.

**შეცვლილი ფაილები (სერვერზე, არა repo-ში):** `~/app/.env`, `~/public_html/index.php`, `~/public_html/.htaccess`, `~/public_html/build` და `~/public_html/storage` (symlink-ები).

---

## 2026-07-14 — WORKLOG პროცესის დამატება

CLAUDE.md-ში დაემატა მუდმივი წესი, რომ ყოველი მნიშვნელოვანი ცვლილების შემდეგ ეს ფაილი უნდა განახლდეს. ამავე დროს შეიქმნა თავად ეს ფაილი და უკან ჩაიწერა სესიის მანძილზე აქამდე გაკეთებული სამუშაო.

**შეცვლილი ფაილები:**
- `CLAUDE.md` — დაემატა "## Work log" სექცია.
- `docs/WORKLOG.md` — ახალი ფაილი (ეს ფაილი).

---

## 2026-07-14 — მენიუს ზოლის sticky (ფიქსირებული) ჩვენება სქროლის დროს

საიტის header (ლოგო + მენიუ + ენების გადამრთველი) გახდა `position: sticky`, რომ სქროლის დროს ეკრანის თავზე დარჩეს ადგილზე. ზედა საკონტაქტო ზოლი (ტელეფონი/მეილი, თუ ჩართულია) ჩვეულებრივად სქროლდება ზემოთ და ქრება — მხოლოდ თავად მენიუს ზოლია ფიქსირებული. ბრაუზერში დადასტურდა Playwright-ით (სქროლი 1200px-ზე, header კვლავ ზედა პოზიციაზეა).

**შეცვლილი ფაილები:**
- `resources/views/layouts/site.blade.php` — `<header>`-ს დაემატა `sticky top-0 z-30` კლასები.

---

## 2026-07-14 — პროდუქტების "შემობრუნებადი" (flip) ბარათის სტილი

პროდუქტების row-ის (grid რეჟიმი) ბარათის სტილს დაემატა ახალი ვარიანტი "flip" არსებული `bordered`/`frameless`-ის გვერდით: ჩვეულებრივ მდგომარეობაში სურათი + სათაური (ქვედა გრადიენტულ ზოლზე) ჩანს, mouse hover-ზე ბარათი 3D-ში (`rotateY`) შემობრუნდება და უკანა მხარეს გამოჩნდება პროდუქტის მოკლე აღწერა და ფასი, mouse leave-ზე კი უკან უბრუნდება საწყის მდგომარეობას. ბრაუზერში დადასტურდა Playwright-ით სამივე მდგომარეობა (საწყისი / hover / leave).

**შეცვლილი ფაილები:**
- `app/Models/Row.php` — `cardStyle()`-მა და ახალმა `CARD_STYLES` კონსტანტამ მხარი დაუჭირა `flip` მნიშვნელობას.
- `app/Http/Controllers/Admin/RowController.php` — `card_style` ვალიდაციის წესი განახლდა (`in:bordered,frameless,flip`).
- `resources/views/admin/rows/edit.blade.php` — "ბარათის სტილი" dropdown-ში დაემატა "შემობრუნებადი" ვარიანტი.
- `resources/views/site/rows/products.blade.php` — grid რეჟიმისთვის დაემატა ახალი branch flip ბარათის მარკაპისთვის.
- `resources/css/app.css` — დაემატა `.product-flip-card*` სტილები (perspective, 3D flip transform, backface-visibility).
