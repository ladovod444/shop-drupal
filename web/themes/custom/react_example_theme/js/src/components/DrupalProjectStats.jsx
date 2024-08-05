import React, { useState, useEffect } from "react";
import PropTypes from "prop-types";
import { projects } from '../config'

const DrupalProjectStats = () => {
  const [project, setProject] = useState('drupal');
  const [usage, setUsage] = useState(null);

  // const projects = [
  //   {key: 'drupal', value: 'Drupal'},
  //   {key: 'marquee', value: 'Marquee'},
  //   {key: 'token', value: 'Token'},
  //   {key: 'pathauto', value: 'Pathauto'},
  // ];

  useEffect(() => {
    setUsage(false);
    const data = fetch(
      `https://www.drupal.org/api-d7/node.json?field_project_machine_name=${project}`
    )
      .then(response => response.json())
      .then(result => {
        if (result.list[0].project_usage) {
          setUsage(result.list[0].project_usage);
        }
      })
      .catch(error => console.log("error", error));
  }, [project]);

  return (
    <div>
      <div>
        Choose a project:
        <br/>
        {projects.map(elem =>
          <button key={elem.key} onClick={() => setProject(elem.key)}>{elem.value}</button>,
        )

        }
        {/*<button onClick={() => setProject('drupal')}>Drupal core</button>*/}
        {/*<button onClick={() => setProject('marquee')}>Marquee</button>*/}
        {/*<button onClick={() => setProject('token')}>Token</button>*/}
        {/*<button onClick={() => setProject('pathauto')}>Pathauto</button>*/}
        {/*<button onClick={() => setProject('ctools')}>Ctools</button>*/}
      </div>
      <hr />
      <div className="project--name">
        Usage stats for <strong>{project}</strong> by version:
        {usage ? (
          <ul>
            {Object.keys(usage).map(key => (
              <StatsItem count={usage[key]} version={key} key={key} />
            ))}
          </ul>
        ) : (
          <p>fetching data ...</p>
        )}
      </div>
    </div>
  );
};

// Provide type checking for props. Think of this as documentation for what
// props a component accepts.
// https://reactjs.org/docs/typechecking-with-proptypes.html
DrupalProjectStats.propTypes = {
  projectName: PropTypes.string.required
};

// Set a default value for any required props.
DrupalProjectStats.defaultProps = {
  projectName: 'drupal',
};

/**
 * Another component: this one displays usage statistics for a specific version
 * of Drupal. It's not exported, so it can only be used in this file's scope.
 * Breaking things up like this can help make your code easier to maintain.
 */
const StatsItem = ({ count, version }) => (
  <li>
    <strong>{version}</strong>: {count}
  </li>
);

StatsItem.propTypes = {
  count: PropTypes.number,
  version: PropTypes.string,
};

export default DrupalProjectStats;
