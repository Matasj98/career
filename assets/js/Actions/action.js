export const setProfilesList = profiles =>({
    type: 'setProfilesList',
    profiles
})
//////////////////////////////////////

export const setSelectedProfile = profile => ({
    type: 'setSelectedProfile',
    profile
})

/////////////////////////////////////////////////////
export const setEmail = email => ({
    type: 'setEmail',
    email
})

export const setFullName = name => ({
    type: 'setFullName',
    name
})

export const setUserId = userId => ({
    type: 'setUserId',
    userId
})

export const setTitle = title => ({
    type: 'setTitle',
    title
})

export const setCareerFormId = formId => ({
    type: 'setCareerFormId',
    formId
})

export const setProfessionId = professionId => ({
    type: 'setProfessionId',
    professionId
})

export const setRoles = roles => ({
    type: 'setRoles',
    roles
})

export const setLogged = logged => ({
    type: 'setLogged',
    logged
})

export const setTeams = teams => ({
    type: 'setTeams',
    teams
})

//////////////////////

export const setAnswers = (criteriaId, choiceId) =>({
    type: 'setAnswers',
    criteriaId,
    choiceId
})

// export const removeAnswer = (criteriaId, choiceId) => ({
//     type: 'removeAnswer',
//     criteriaId,
//     choiceId
// })