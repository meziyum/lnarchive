
import React from 'react';

export default function Novel_Item( props ){
    return (
        <div className="novel-entry-col archive-entry-col col-lg-2 col-md-3 col-sm-3 col-4">
            <div className="novel-entry archive-entry">
                <a id={props.id} className="novel-link" href={props.link}>
                    <img className="novel-cover" width="900" height="1280" srcSet={props.novel_cover}>
                    </img>
                </a>
            </div>
        </div>
    );
}
