import styles from "./Footer.module.scss";
import logo from "../../assets/logo.svg";

const Footer = () => {
  return (
    <footer className={styles.footer}>
      <div className={styles.container}>
        <div className={styles.grid}>
          {/* Colonne 1 – Branding */}
          <div className={styles.brand}>
            <img src={logo} alt="Logo Take-Hardvantage" className={styles.logo} />
            <p className={styles.description} >
              Take-Hardvantage est une plateforme d’outils pour les sportifs
              passionnés.
            </p>
            <ul className={styles.socials}>
              <li>
                <a href="#" className={styles.icon} aria-label="Twitter">
                  <i className="fab fa-twitter" aria-hidden="true"></i>
                </a>
              </li>
              <li>
                <a href="#" className={styles.icon} aria-label="Facebook">
                  <i className="fab fa-facebook-f" aria-hidden="true"></i>
                </a>
              </li>
              <li>
                <a href="#" className={styles.icon} aria-label="Instagram">
                  <i className="fab fa-instagram" aria-hidden="true"></i>
                </a>
              </li>
              <li>
                <a href="#" className={styles.icon} aria-label="GitHub">
                  <i className="fab fa-github" aria-hidden="true"></i>
                </a>
              </li>
            </ul>
          </div>
          <div className={styles.section}>
            <h5>Entreprise</h5>
            <ul>
              <li>
                <a href="#">À propos</a>
              </li>
              <li>
                <a href="#">Fonctionnalités</a>
              </li>
              <li>
                <a href="#">Projets</a>
              </li>
              <li>
                <a href="#">Carrière</a>
              </li>
            </ul>
          </div>
          <div className={styles.section}>
            <h5>Aide</h5>
            <ul>
              <li>
                <a href="#">Support client</a>
              </li>
              <li>
                <a href="#">Livraison</a>
              </li>
              <li>
                <a href="/conditions-generales">Conditions générales</a>
              </li>
              <li>
                <a href="/confidentialite">Confidentialité</a>
              </li>
            </ul>
          </div>
        </div>
        <hr />
        <p className={styles.copyright}>
          &copy; {new Date().getFullYear()} Take-Hardvantage. Tous droits
          réservés.
        </p>
      </div>
    </footer>
  );
};

export default Footer;
