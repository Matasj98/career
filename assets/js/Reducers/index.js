import { combineReducers } from 'redux';
import { managerPage } from './managerPage';
import { user } from './User';
import { trackUserChanges } from './trackUserChanges';
import { answerListUserSide } from './answerListUserSide';
import { answerListTeamLeadSide } from './answerListTeamLeadSide';
//rootReducer

const appReducer = combineReducers({
    managerPage,
    user,
    trackUserChanges,
    answerListUserSide,
    answerListTeamLeadSide
})

export const allReducer = (state, action) =>{
    if(action.type === "RESET_APP"){
        state = undefined;
    }

    return appReducer(state, action);
}