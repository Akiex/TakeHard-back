import Button from "../../components/Button/Button";
import Header from "../../components/Header/Header";
import Card from "../../components/Card/Card";
import Footer from "../../components/Footer/Footer";
const Home = () => {
  const handleClick = () => {
    alert("Bouton cliqué !");
  };

  return (
    <div>
      <Header />
      <section>
        <h2>Templates à la une</h2>
        <article className="container">
          <Card />
          <Card />
          <Card />
        </article>
      </section>
      <section>
        <h2>Exercice à la une</h2>
        <article className="container">
          <Card />
          <Card />
          <Card />
        </article>
      </section>
      <Footer />
    </div>
  );
};

export default Home;
