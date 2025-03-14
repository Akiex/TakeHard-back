import styles from "./BO.module.scss";
import Button from "../../components/Button/Button";
import Header from "../../components/Header/Header";
import Footer from "../../components/Footer/Footer";

const BO = () => {
  return (
    <div className={styles.BO}>
      <Header />
      <main>
        <section>
          <h2> Section User</h2>
          <table>
            <thead>
              <tr>
                <th>Id</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>John</td>
                <td>Doe</td>
                <td>7Y3t8@example.com</td>
                <td>Admin</td>
                <td>
                  <Button text="✎"></Button> <Button text="🗑️"></Button>
                </td>
              </tr>
            </tbody>
          </table>
        </section>
        <section>
          <h2> Section Template</h2>
          <table>
            <thead>
              <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Template 1</td>
                <td>Description du template 1</td>
                <td>
                  <Button text="✎"></Button> <Button text="🗑️"></Button>
                </td>
              </tr>
            </tbody>
          </table>
        </section>
        <section>
          <h2> Section Exercice</h2>
          <table>
            <thead>
              <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Description</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Exercice 1</td>
                <td>Description de l'exercice 1</td>
                <td>
                  <Button text="✎"></Button> <Button text="🗑️"></Button>
                </td>
              </tr>
            </tbody>
          </table>
        </section>
      </main>
      <Footer />
    </div>
  );
};

export default BO;
