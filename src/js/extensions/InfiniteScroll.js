
import React from 'react';
import PropTypes from 'prop-types';

/**
 * A dummy component which is used to trigger the infinite scroll like behavior when in view using the triggerOnView higher ordered component
 * @param {Object} props - The props passed to the component.
 * @param {React.RefObject} props.componentRef - A ref object used to get a reference to the rendered div element.
 * @return {React.ReactElement} A React element representing a div with the given `componentRef` as its ref attribute.
 */
function Dummy(props) {
    return (
        <div ref={props.componentRef}/>
    );
}

Dummy.propTypes = {
    componentRef: PropTypes.object.isRequired,
};

/**
 * A higher-order component (HOC) that attaches an Intersection Observer to a child component and triggers an action when it comes into view.
 * @param {React.ComponentType} WrappedComponent - The component to which the Intersection Observer will be attached.
 * @return {React.ComponentType} A new component that renders the `WrappedComponent` and provides it with a ref to attach the Intersection Observer to it.
 */
function triggerOnView(WrappedComponent) {
    return function triggerOnView(props) {
        const componentRef = React.useRef(null);
        const [timerRunning, setTimerRunning] = React.useState(false);

        React.useEffect(() => {
            const observer = new IntersectionObserver(([entry]) => {
                if (entry.intersectionRatio >= 1.0 && !timerRunning) {
                    setTimerRunning(true);
                    const timerId = setTimeout(() => {
                        setTimerRunning(false);
                        props.handleInView();
                    }, 750);
                    return () => {
                        clearTimeout(timerId);
                    };
                }
            }, {threshold: 1.0});

            if (componentRef.current) {
                observer.observe(componentRef.current);
            }

            return () => {
                if (componentRef.current) {
                    observer.unobserve(componentRef.current);
                }
            };
        }, []);

        return <WrappedComponent componentRef={componentRef} />;
    };
}

const InfiniteScroll = triggerOnView(Dummy);
export default InfiniteScroll;
