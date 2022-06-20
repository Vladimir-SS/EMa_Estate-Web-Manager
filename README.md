<div align="center">
  <h1>EMa_Estate-Web-Manager</h1>
  
  <p>
    E necesara o aplicatie Web menita a gestiona eficient tranzactiile imobiliare. Sistemul va permite managementul unor imobile spre vanzare si/sau inchiriere, inclusiv informatii precum descriere, pret, coordonatele locatiei, date de contact, starea cladirii, facilitati oferite, riscuri posibile etc. Pentru localizarea facila, se va recurge la un serviciu de cartografiere (e.g., OpenStreetMap). In plus, se va oferi si posibilitatea atasarii de straturi suplimentare pentru vizualizarea unor informatii de interes -- e.g. diversele tipuri de poluare, nivelul de aglomeratie, numarul de raportari de jafuri, costul mediu de trai, temperatura medie anuala, existenta parcarilor ori altor obiective de interes (i.e. magazine) si altele. Pentru generarea diverselor straturi se poate recurge la date agregate existente in cadrul unor platforme sociale (e.g., Twitter, Facebook). De exemplu, pentru stratul poluare fonica, se pot agrega resurse marcate cu tag-ul "#noise" ori "#smog". Utilizatorii interesati de inchirierea/cumpararea unei locuinte (e.g. apartament, casa, loc de veci etc.) vor putea efectua diverse operatiuni folosind harta pusa la dispozitie: selectarea zonei de interes pentru afisarea optiunilor existente, selectarea diverselor straturi pentru luarea deciziei, filtrare in functie de alte criterii (e.g., pret, suprafata, facilitati). Funcționalitatea va fi expusa si sub forma unui serviciu Web REST/GraphQL. Optional, se poate utiliza Geolocation API pentru furnizarea de imobile aflate in vecinatatea utilizatorului.

    Design: https://www.figma.com/file/MgwuSLcZmdNURq57CIeqev/Page?node-id=0%3A1
    C4 Diagram: https://drive.google.com/file/d/1rgzeKE8oLMw3d9o57hZfLW9svb4mI4m4/view?usp=sharing
    C4 Diagram SVG: https://drive.google.com/file/d/1Cbz6OK1aGAnhhoCDQAUHgx9eqIh_58nO/view?usp=sharing
    DB-Diagram: https://drive.google.com/file/d/1PkayucyTL7Cpm0jkeUCr5ysG6UDfpLbO/view?usp=sharing
  </p>
  
  
<!-- Badges -->
<p>
  <a href="https://github.com/DeliTrbat/EMa_Estate-Web-Manager/graphs/contributors">
    <img src="https://img.shields.io/github/contributors/DeliTrbat/EMa_Estate-Web-Manager" alt="contributors" />
  </a>
  <a href="">
    <img src="https://img.shields.io/github/last-commit/DeliTrbat/EMa_Estate-Web-Manager" alt="last update" />
  </a>
  <a href="https://github.com/DeliTrbat/EMa_Estate-Web-Manager/network/members">
    <img src="https://img.shields.io/github/forks/DeliTrbat/EMa_Estate-Web-Manager" alt="forks" />
  </a>
  <a href="https://github.com/DeliTrbat/EMa_Estate-Web-Manager/stargazers">
    <img src="https://img.shields.io/github/stars/DeliTrbat/EMa_Estate-Web-Manager" alt="stars" />
  </a>
  <a href="https://github.com/DeliTrbat/EMa_Estate-Web-Manager/issues/">
    <img src="https://img.shields.io/github/issues/DeliTrbat/EMa_Estate-Web-Manager" alt="open issues" />
  </a>
</p>
   
</div>

Start server:
```bash
cd public
php -c ../php.ini -S localhost:8000
```