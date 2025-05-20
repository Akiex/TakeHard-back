import styles from "./Footer.module.scss";
import logo from "../../assets/logo.svg";

const Footer = () => {
  return (
    <footer className={styles.footer}>
      <div className={styles.container}>
        <div className={styles.grid}>
          {/* Colonne 1 - Branding */}
          <div className={styles.brand}>
            <img src={logo} alt="Logo" className={styles.logo} />
            <p className={styles.description}>
              Take-Hardvantage est une plateforme d’outils pour les sportifs passionnés.
            </p>
            <ul className={styles.socials}>
              <li><a href="#" className={styles.icon}><i className="fab fa-twitter"></i></a></li>
              <li><a href="#" className={styles.icon}><i className="fab fa-facebook-f"></i></a></li>
              <li><a href="#" className={styles.icon}><i className="fab fa-instagram"></i></a></li>
              <li><a href="#" className={styles.icon}><i className="fab fa-github"></i></a></li>
            </ul>
          </div>

          {/* Colonne 2 - Entreprise */}
          <div className={styles.section}>
            <h4>Entreprise</h4>
            <ul>
              <li><a href="#">À propos</a></li>
              <li><a href="#">Fonctionnalités</a></li>
              <li><a href="#">Projets</a></li>
              <li><a href="#">Carrière</a></li>
            </ul>
          </div>

          {/* Colonne 3 - Aide */}
          <div className={styles.section}>
            <h4>Aide</h4>
            <ul>
              <li><a href="#">Support client</a></li>
              <li><a href="#">Livraison</a></li>
              <li><a href="#">Conditions générales</a></li>
              <li><a href="#">Confidentialité</a></li>
            </ul>
          </div>
        </div>

        <hr />
        <p className={styles.copyright}>
          &copy; {new Date().getFullYear()} Take-Hardvantage. Tous droits réservés.
        </p>
      </div>
    </footer>
  );
};

export default Footer;