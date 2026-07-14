# სამუშაო ჟურნალი (WORKLOG)

მუდმივი წესი ამ ფაილის შესახებ განსაზღვრულია `CLAUDE.md`-ში ("## Work log" სექცია): ყოველი მნიშვნელოვანი ცვლილების შემდეგ აქ ემატება ჩანაწერი — თარიღი, რა გაკეთდა, რომელი ფაილები შეიცვალა. უახლესი ჩანაწერი ზემოთაა.

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
