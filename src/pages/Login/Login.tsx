import { useState } from "react";
import { useNavigate, Link } from "react-router-dom";
import { useAuth } from "../../context/AuthContext";
import { loginService } from "./../../services/autServices";
import styles from "./Login.module.scss";
import Button from "../../components/Button/Button";

const Login = () => {
  const { login } = useAuth();
  const navigate = useNavigate();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState<string | null>(null);
  const [loading, setLoading] = useState(false);

  const handleSubmit = async () => {
    setLoading(true);
    setError(null);

    try {
      // Appel centralisé à loginService
      const { token, isAdmin } = await loginService(email, password);
      // Mise à jour du contexte
      login(token, isAdmin);
      // Redirection
      navigate("/");
    } catch (err: any) {
      setError(err.message || "Erreur de connexion");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className={styles.loginForm}>
      <h2>Connexion</h2>
      {error && <div className={styles.error}>{error}</div>}
      <form onSubmit={handleSubmit} className={styles.loginForm}>
        <label htmlFor="email">Email :</label>
        <input
          type="email"
          id="email"
          value={email}
          onChange={(e) => setEmail(e.target.value)}
          required
        />

        <label htmlFor="password">Mot de passe :</label>
        <input
          type="password"
          id="password"
          value={password}
          onChange={(e) => setPassword(e.target.value)}
          required
        />


      </form>
      <p>
        Pas encore inscrit ? <Link to="/register">Créer un compte</Link>
      </p>
          <Button text={loading ? "Connexion en cours..." : "Se connecter"} variant="secondary" type="submit" onClick={handleSubmit} />
    </div>
  );
};

export default Login;